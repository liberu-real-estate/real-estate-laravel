#!/bin/bash
# Setup script for the Liberu Real Estate project.
#
# Usage:
#   ./setup.sh                        Interactive mode (shows menu)
#   ./setup.sh standalone             Standalone installation
#   ./setup.sh docker                 Docker installation
#   ./setup.sh k8s                    Kubernetes installation
#   ./setup.sh standalone --ci        Non-interactive (CI/CD) mode
#   ./setup.sh --help                 Show this help

set -euo pipefail

# Colors for output
RED='\e[91m'
GREEN='\e[92m'
YELLOW='\e[93m'
BLUE='\e[94m'
RESET='\e[39m'

# Global flags
NON_INTERACTIVE=false
SKIP_TESTS=false
SKIP_BUILD=false

# Parse global flags
for arg in "$@"; do
    case $arg in
        --ci|--non-interactive) NON_INTERACTIVE=true ;;
        --skip-tests) SKIP_TESTS=true ;;
        --skip-build) SKIP_BUILD=true ;;
        --help|-h)
            echo "Usage: $0 [standalone|docker|k8s] [--ci] [--skip-tests] [--skip-build]"
            echo ""
            echo "Commands:"
            echo "  standalone    Local/standalone installation"
            echo "  docker        Docker Compose installation"
            echo "  k8s           Kubernetes installation"
            echo ""
            echo "Flags:"
            echo "  --ci / --non-interactive  Skip interactive prompts (for CI/CD)"
            echo "  --skip-tests              Skip running tests after install"
            echo "  --skip-build              Skip npm build step"
            exit 0
            ;;
    esac
done

print_message() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${RESET}"
}

print_header() {
    echo ""
    echo "=================================="
    echo "$1"
    echo "=================================="
    echo ""
}

print_error() {
    print_message "$RED" "ERROR: $1" >&2
}

print_success() {
    print_message "$GREEN" "OK: $1"
}

print_info() {
    print_message "$BLUE" "INFO: $1"
}

print_warning() {
    print_message "$YELLOW" "WARN: $1"
}

command_exists() {
    command -v "$1" >/dev/null 2>&1
}

ask_yes_no() {
    local prompt=$1
    local default=${2:-y}
    if [ "$NON_INTERACTIVE" = true ]; then
        return 0
    fi
    local yn
    while true; do
        read -rp "$prompt (y/n) [${default}]: " yn
        yn="${yn:-$default}"
        case "$yn" in
            [Yy]*) return 0 ;;
            [Nn]*) return 1 ;;
            *) print_warning "Please answer yes or no." ;;
        esac
    done
}

ensure_composer() {
    if command_exists composer; then
        print_success "Composer is installed"
        COMPOSER_CMD="composer"
        return 0
    fi

    print_warning "Composer not found. Downloading composer.phar..."

    if ! command_exists curl; then
        print_error "curl is required to download Composer. Install curl or Composer manually."
        return 1
    fi
    if ! command_exists php; then
        print_error "PHP is required. Please install PHP first."
        return 1
    fi

    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --quiet
    php -r "unlink('composer-setup.php');"

    if [ -f "composer.phar" ]; then
        print_success "Composer.phar downloaded"
        COMPOSER_CMD="php composer.phar"
        return 0
    fi

    print_error "Failed to download composer.phar"
    return 1
}

install_composer_dependencies() {
    print_header "COMPOSER INSTALL"

    if [ -d "vendor" ] && [ -f "vendor/autoload.php" ] && [ "$NON_INTERACTIVE" = false ]; then
        if ! ask_yes_no "Vendor directory exists. Reinstall composer dependencies?"; then
            print_success "Skipping composer install"
            return 0
        fi
    fi

    if ! ensure_composer; then
        print_error "Cannot proceed without Composer"
        return 1
    fi

    print_info "Running: $COMPOSER_CMD install --no-interaction --prefer-dist --optimize-autoloader"
    eval "$COMPOSER_CMD install --no-interaction --prefer-dist --optimize-autoloader"
    print_success "Composer dependencies installed"
}

install_npm_dependencies() {
    print_header "NPM INSTALL"

    if ! command_exists npm; then
        print_warning "npm not installed. Skipping npm install."
        return 0
    fi

    if [ -d "node_modules" ] && [ "$NON_INTERACTIVE" = false ]; then
        if ! ask_yes_no "node_modules exists. Reinstall npm dependencies?"; then
            print_success "Skipping npm install"
            return 0
        fi
    fi

    print_info "Running: npm ci"
    if npm ci --prefer-offline 2>/dev/null || npm install; then
        print_success "NPM dependencies installed"
        return 0
    fi

    print_warning "NPM install failed"
    return 1
}

build_frontend_assets() {
    if [ "$SKIP_BUILD" = true ]; then
        print_info "Skipping npm build (--skip-build)"
        return 0
    fi

    print_header "NPM BUILD"

    if ! command_exists npm; then
        print_warning "npm not installed. Skipping asset build."
        return 0
    fi

    print_info "Running: npm run build"
    if npm run build; then
        print_success "Frontend assets built"
        return 0
    fi

    print_warning "npm build failed"
    return 1
}

setup_env_file() {
    if [ ! -f ".env" ]; then
        print_info "Copying .env.example to .env"
        cp .env.example .env
        if [ "$NON_INTERACTIVE" = false ]; then
            print_warning "Please edit .env with your database credentials before continuing."
            read -rp "Press Enter after editing .env..."
        fi
    else
        print_success ".env file already exists"
    fi
}

run_artisan_setup() {
    print_header "LARAVEL SETUP"

    if ! php artisan key:generate --ansi 2>/dev/null; then
        if grep -q "^APP_KEY=$" .env 2>/dev/null || ! grep -q "^APP_KEY=" .env 2>/dev/null; then
            php artisan key:generate --ansi --force
        fi
    fi
    print_success "Application key set"

    print_info "Running migrations..."
    if [ "$NON_INTERACTIVE" = true ]; then
        php artisan migrate --force
    else
        php artisan migrate
    fi
    print_success "Database migrated"

    print_info "Seeding database..."
    if [ "$NON_INTERACTIVE" = true ]; then
        php artisan db:seed --force
    else
        php artisan db:seed
    fi
    print_success "Database seeded"

    print_info "Generating Filament Shield permissions..."
    if php artisan shield:generate --all --no-interaction 2>/dev/null; then
        print_success "Filament Shield permissions generated"
    else
        print_warning "Filament Shield not available or failed — skipping"
    fi

    print_info "Running optimize:clear..."
    php artisan optimize:clear
    php artisan route:clear
    php artisan config:clear
    php artisan view:clear
    print_success "Caches cleared"

    print_info "Creating storage symlink..."
    php artisan storage:link --force 2>/dev/null || true
}

run_health_check() {
    print_header "HEALTH CHECK"

    local errors=0

    # Check PHP version
    php_version=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')
    if [ "$(echo "$php_version >= 8.2" | bc -l 2>/dev/null || echo 1)" = "1" ]; then
        print_success "PHP version: $php_version"
    else
        print_warning "PHP $php_version detected; PHP >= 8.2 recommended"
    fi

    # Check required PHP extensions
    for ext in pdo pdo_mysql mbstring openssl curl fileinfo xml json zip; do
        if php -m 2>/dev/null | grep -qi "^${ext}$"; then
            print_success "PHP extension: $ext"
        else
            print_warning "PHP extension missing: $ext"
            ((errors++)) || true
        fi
    done

    # Check .env exists
    if [ -f ".env" ]; then
        print_success ".env file present"
    else
        print_error ".env file missing"
        ((errors++)) || true
    fi

    # Check vendor exists
    if [ -f "vendor/autoload.php" ]; then
        print_success "Composer dependencies installed"
    else
        print_error "Composer dependencies not installed"
        ((errors++)) || true
    fi

    # Check storage is writable
    if [ -w "storage" ]; then
        print_success "Storage directory writable"
    else
        print_warning "Storage directory not writable - run: chmod -R 775 storage bootstrap/cache"
        ((errors++)) || true
    fi

    if [ "$errors" -gt 0 ]; then
        print_warning "Health check: $errors issue(s) found"
    else
        print_success "Health check: all systems go"
    fi
}

run_tests() {
    if [ "$SKIP_TESTS" = true ]; then
        print_info "Skipping tests (--skip-tests)"
        return 0
    fi

    print_header "RUNNING TESTS"

    if [ -f "vendor/bin/phpunit" ]; then
        if php artisan test --parallel 2>/dev/null || php artisan test; then
            print_success "Tests passed"
        else
            print_warning "Some tests failed. Review output above."
        fi
    else
        print_warning "PHPUnit not found. Skipping tests."
    fi
}

install_standalone() {
    print_header "STANDALONE INSTALLATION"

    run_health_check
    setup_env_file
    install_composer_dependencies
    install_npm_dependencies
    build_frontend_assets
    run_artisan_setup
    run_tests

    echo ""
    print_success "=================================="
    print_success "    INSTALLATION COMPLETE"
    print_success "=================================="
    echo ""
    print_info "Start the development server with: php artisan serve"
    print_info "Start queue workers with: php artisan horizon"
    print_info "Admin panel available at: /admin"

    if [ "$NON_INTERACTIVE" = false ]; then
        if ask_yes_no "Start the development server now?"; then
            php artisan serve
        fi
    fi
}

install_docker() {
    print_header "DOCKER INSTALLATION"

    if ! command_exists docker; then
        print_error "Docker is not installed. Visit: https://docs.docker.com/get-docker/"
        exit 1
    fi
    print_success "Docker is installed ($(docker --version))"

    local compose_cmd=""
    if command_exists docker-compose; then
        compose_cmd="docker-compose"
    elif docker compose version >/dev/null 2>&1; then
        compose_cmd="docker compose"
    else
        print_error "Docker Compose not found. Visit: https://docs.docker.com/compose/install/"
        exit 1
    fi
    print_success "Docker Compose available"

    setup_env_file

    print_info "Building and starting Docker containers..."
    if $compose_cmd up -d --build; then
        print_success "Docker containers started"
        print_info "Application available at: http://localhost:8000"
        print_info "View logs: ${compose_cmd} logs -f app"
        print_info "Stop: ${compose_cmd} down"
    else
        print_error "Failed to start Docker containers"
        exit 1
    fi

    # Wait for app to be ready
    print_info "Waiting for application to be ready..."
    local retries=30
    while [ $retries -gt 0 ]; do
        if curl -sf http://localhost:8000/up >/dev/null 2>&1; then
            print_success "Application is ready at http://localhost:8000"
            break
        fi
        retries=$((retries - 1))
        sleep 2
    done
    [ $retries -eq 0 ] && print_warning "Application did not become ready in time; check logs"
}

install_kubernetes() {
    print_header "KUBERNETES INSTALLATION"

    if ! command_exists kubectl; then
        print_error "kubectl not installed. Visit: https://kubernetes.io/docs/tasks/tools/"
        exit 1
    fi
    print_success "kubectl installed ($(kubectl version --client --short 2>/dev/null || kubectl version --client))"

    K8S_DIR="k8s"
    [ ! -d "$K8S_DIR" ] && [ -d "kubernetes" ] && K8S_DIR="kubernetes"

    if [ ! -d "$K8S_DIR" ]; then
        print_error "No Kubernetes configuration directory found (k8s/ or kubernetes/)"
        exit 1
    fi
    print_info "Using Kubernetes configs from: $K8S_DIR/"

    setup_env_file

    # Verify cluster access
    if ! kubectl cluster-info >/dev/null 2>&1; then
        print_error "Cannot connect to Kubernetes cluster. Check your kubeconfig."
        exit 1
    fi
    print_success "Kubernetes cluster accessible"

    apply_if_exists() {
        local file="$K8S_DIR/$1"
        if [ -f "$file" ]; then
            print_info "Applying $1..."
            kubectl apply -f "$file"
        fi
    }

    # Use kustomize if kustomization.yaml is present
    if [ -f "$K8S_DIR/kustomization.yaml" ]; then
        print_info "Applying via kustomize..."
        kubectl apply -k "$K8S_DIR/"
        print_success "Kustomize resources applied"
    else
        # Apply in dependency order
        apply_if_exists "namespace.yaml"
        apply_if_exists "configmap.yaml"
        apply_if_exists "secret.yaml"
        apply_if_exists "pvc.yaml"
        apply_if_exists "deployment.yaml"
        apply_if_exists "service.yaml"
        apply_if_exists "ingress.yaml"
        apply_if_exists "hpa.yaml"
        apply_if_exists "monitoring.yaml"
        apply_if_exists "network-policy.yaml"
        apply_if_exists "resource-quota.yaml"

        # Apply any remaining yaml files
        for f in "$K8S_DIR"/*.yaml; do
            filename=$(basename "$f")
            case "$filename" in
                namespace.yaml|configmap.yaml|secret.yaml|pvc.yaml|deployment.yaml|service.yaml|ingress.yaml|hpa.yaml|monitoring.yaml|network-policy.yaml|resource-quota.yaml|kustomization.yaml) ;;
                *) print_info "Applying $filename..."; kubectl apply -f "$f" ;;
            esac
        done
    fi

    print_success "Kubernetes resources applied"
    echo ""
    print_info "Check pod status:  kubectl get pods -n real-estate"
    print_info "View services:     kubectl get svc -n real-estate"
    print_info "View ingress:      kubectl get ingress -n real-estate"
    print_info "View logs:         kubectl logs -f deployment/real-estate-app -n real-estate"

    # Wait for rollout if not CI
    if [ "$NON_INTERACTIVE" = false ] || [ "${WAIT_FOR_ROLLOUT:-false}" = "true" ]; then
        print_info "Waiting for deployment rollout..."
        kubectl rollout status deployment/real-estate-app -n real-estate --timeout=300s || \
            print_warning "Rollout did not complete within 5 minutes; check pod status"
    fi
}

# Main entry point
main() {
    local command="${1:-}"

    # Strip flags from command
    case "$command" in
        --ci|--non-interactive|--skip-tests|--skip-build|--help|-h) command="" ;;
    esac

    case "$command" in
        standalone)
            install_standalone
            ;;
        docker)
            install_docker
            ;;
        k8s|kubernetes)
            install_kubernetes
            ;;
        "")
            if [ "$NON_INTERACTIVE" = true ]; then
                print_error "Non-interactive mode requires a command: standalone, docker, or k8s"
                exit 1
            fi

            clear
            print_header "LIBERU REAL ESTATE - INSTALLER"
            echo "  1) Standalone (Local development/production)"
            echo "  2) Docker (Containerized deployment)"
            echo "  3) Kubernetes (K8s cluster deployment)"
            echo "  4) Exit"
            echo ""

            while true; do
                read -rp "Enter your choice (1-4): " choice
                case $choice in
                    1) install_standalone; break ;;
                    2) install_docker; break ;;
                    3) install_kubernetes; break ;;
                    4) print_info "Installation cancelled"; exit 0 ;;
                    *) print_warning "Invalid choice. Please enter 1, 2, 3, or 4." ;;
                esac
            done
            ;;
        *)
            print_error "Unknown command: $command. Run with --help for usage."
            exit 1
            ;;
    esac
}

main "$@"
