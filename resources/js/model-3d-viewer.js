import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

/**
 * 3D Model Viewer Component
 * Provides an interactive 3D model viewer for property models
 */
export class Model3DViewer {
    constructor(containerId, modelUrl) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error(`Container with id "${containerId}" not found`);
            return;
        }

        this.modelUrl = modelUrl;
        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        this.model = null;
        this.animationId = null;

        this.init();
    }

    init() {
        // Check for WebGL support
        if (!this.checkWebGLSupport()) {
            this.showFallbackMessage();
            return;
        }

        // Create scene
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0xf0f0f0);

        // Create camera
        const width = this.container.clientWidth;
        const height = this.container.clientHeight;
        this.camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
        this.camera.position.z = 5;
        this.camera.position.y = 2;

        // Create renderer
        this.renderer = new THREE.WebGLRenderer({ antialias: true });
        this.renderer.setSize(width, height);
        this.renderer.setPixelRatio(window.devicePixelRatio);
        this.container.appendChild(this.renderer.domElement);

        // Add lights
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        this.scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(10, 10, 5);
        this.scene.add(directionalLight);

        // Add orbit controls
        this.controls = new OrbitControls(this.camera, this.renderer.domElement);
        this.controls.enableDamping = true;
        this.controls.dampingFactor = 0.05;
        this.controls.screenSpacePanning = false;
        this.controls.minDistance = 2;
        this.controls.maxDistance = 50;
        this.controls.maxPolarAngle = Math.PI / 2;

        // Add grid helper
        const gridHelper = new THREE.GridHelper(10, 10);
        this.scene.add(gridHelper);

        // Load model
        this.loadModel();

        // Handle window resize
        window.addEventListener('resize', () => this.onWindowResize());

        // Start animation loop
        this.animate();
    }

    checkWebGLSupport() {
        try {
            const canvas = document.createElement('canvas');
            return !!(window.WebGLRenderingContext && (
                canvas.getContext('webgl') || 
                canvas.getContext('experimental-webgl')
            ));
        } catch (e) {
            return false;
        }
    }

    showFallbackMessage() {
        this.container.innerHTML = `
            <div class="flex items-center justify-center h-full bg-gray-100 rounded-lg p-8">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">3D Viewer Not Available</h3>
                    <p class="text-gray-600">Your browser doesn't support WebGL, which is required for 3D viewing.</p>
                    <p class="text-sm text-gray-500 mt-2">Please try using a modern browser like Chrome, Firefox, or Safari.</p>
                </div>
            </div>
        `;
    }

    loadModel() {
        const loader = new GLTFLoader();
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'absolute inset-0 flex items-center justify-center bg-white bg-opacity-75';
        loadingDiv.innerHTML = `
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                <p class="mt-2 text-gray-600">Loading 3D Model...</p>
            </div>
        `;
        this.container.appendChild(loadingDiv);

        loader.load(
            this.modelUrl,
            (gltf) => {
                this.model = gltf.scene;
                
                // Center the model
                const box = new THREE.Box3().setFromObject(this.model);
                const center = box.getCenter(new THREE.Vector3());
                this.model.position.sub(center);

                // Scale model to fit viewport
                const size = box.getSize(new THREE.Vector3());
                const maxDim = Math.max(size.x, size.y, size.z);
                const scale = 4 / maxDim;
                this.model.scale.multiplyScalar(scale);

                this.scene.add(this.model);
                this.container.removeChild(loadingDiv);
            },
            (progress) => {
                const percentComplete = (progress.loaded / progress.total) * 100;
                console.log(`Loading 3D model: ${percentComplete.toFixed(2)}%`);
            },
            (error) => {
                console.error('Error loading 3D model:', error);
                this.container.removeChild(loadingDiv);
                this.showErrorMessage();
            }
        );
    }

    showErrorMessage() {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'absolute inset-0 flex items-center justify-center bg-red-50 rounded-lg';
        errorDiv.innerHTML = `
            <div class="text-center p-8">
                <svg class="w-16 h-16 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-lg font-semibold text-red-700 mb-2">Failed to Load 3D Model</h3>
                <p class="text-red-600">There was an error loading the 3D model. Please try again later.</p>
            </div>
        `;
        this.container.appendChild(errorDiv);
    }

    animate() {
        this.animationId = requestAnimationFrame(() => this.animate());
        
        if (this.controls) {
            this.controls.update();
        }
        
        if (this.renderer && this.scene && this.camera) {
            this.renderer.render(this.scene, this.camera);
        }
    }

    onWindowResize() {
        if (!this.container || !this.camera || !this.renderer) return;

        const width = this.container.clientWidth;
        const height = this.container.clientHeight;

        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(width, height);
    }

    dispose() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
        }

        if (this.controls) {
            this.controls.dispose();
        }

        if (this.renderer) {
            this.renderer.dispose();
            if (this.renderer.domElement && this.renderer.domElement.parentNode) {
                this.renderer.domElement.parentNode.removeChild(this.renderer.domElement);
            }
        }

        if (this.model) {
            this.scene.remove(this.model);
        }

        window.removeEventListener('resize', () => this.onWindowResize());
    }

    resetCamera() {
        if (this.camera && this.controls) {
            this.camera.position.set(0, 2, 5);
            this.controls.reset();
        }
    }
}

// Make it available globally for Livewire/Alpine.js
window.Model3DViewer = Model3DViewer;
