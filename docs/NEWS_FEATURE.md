# News and Updates Section

This feature provides a comprehensive news and updates section for the real estate platform, allowing staff to manage news articles and users to stay informed about property market trends, updates, and announcements.

## Features

### Admin Management (Filament)
- **Create/Edit/Delete News**: Staff can manage news articles through the Filament admin panel
- **Rich Text Editor**: Format news content with headings, lists, links, and more
- **Featured Articles**: Mark important articles as featured for homepage display
- **Draft Support**: Save articles as drafts (unpublished) for later publication
- **Scheduled Publishing**: Set future publication dates for articles
- **Author Attribution**: Automatically tracks article authors
- **Search & Filters**: Easily find articles by title, author, or publication status

### Public Access

#### Web Interface (Livewire)
- **News List Page**: Browse all published news articles with search and filtering
- **News Detail Page**: Read full articles with related content suggestions
- **Homepage Widget**: Display latest/featured news on the homepage
- **Responsive Design**: Optimized for mobile, tablet, and desktop viewing

#### REST API
- `GET /api/news` - List all published news (supports pagination and filtering)
- `GET /api/news/latest` - Get latest news articles (limit parameter)
- `GET /api/news/featured` - Get featured news articles (limit parameter)
- `GET /api/news/{slug}` - Get specific news article by slug

## File Structure

```
app/
├── Models/News.php                                    # News model with relationships
├── Http/
│   ├── Controllers/NewsController.php                  # API controller
│   └── Livewire/
│       ├── NewsList.php                                # News listing component
│       ├── NewsDetail.php                              # News detail component
│       └── LatestNews.php                              # Homepage widget component
└── Filament/Staff/Resources/News/
    ├── NewsResource.php                                # Filament resource
    └── Pages/
        ├── ListNews.php                                # List page
        ├── CreateNews.php                              # Create page
        └── EditNews.php                                # Edit page

database/
├── migrations/2024_08_20_000000_create_news_table.php # Database migration
├── factories/NewsFactory.php                           # News factory for testing
└── seeders/NewsSeeder.php                             # Sample data seeder

resources/views/livewire/
├── news-list.blade.php                                 # News listing view
├── news-detail.blade.php                               # News detail view
└── latest-news.blade.php                               # Homepage widget view

routes/
├── api.php                                             # API routes for news
└── web.php                                             # Web routes for news pages
```

## Setup Instructions

### 1. Run Migration
```bash
php artisan migrate
```

### 2. (Optional) Seed Sample Data
```bash
php artisan db:seed --class=NewsSeeder
```

This will create:
- 5 featured news articles with real estate content
- 10 random news articles
- 3 draft articles

### 3. Access Admin Panel
Navigate to your Filament admin panel (e.g., `/staff`) and look for "News" in the "Content" section of the navigation menu.

### 4. Add News to Homepage (Optional)
To display latest news on your homepage, add this to your home view:

```blade
@livewire('latest-news', ['limit' => 3, 'showFeatured' => true])
```

Or in your HomeController:

```php
use App\Models\News;

public function index()
{
    $latestNews = News::published()->featured()->limit(3)->get();
    return view('home', compact('latestNews'));
}
```

## Usage Examples

### Creating News via Admin Panel
1. Navigate to Staff Panel → Content → News
2. Click "Create"
3. Fill in the form:
   - **Title**: Will auto-generate slug
   - **Excerpt**: Optional brief summary
   - **Content**: Rich text content (supports HTML)
   - **Publish Date**: Leave empty for draft, set to now for immediate publish, or future date for scheduled
   - **Featured**: Toggle to display on homepage
4. Click "Save"

### API Usage Examples

**Get Latest News:**
```bash
curl https://yourdomain.com/api/news/latest?limit=5
```

**Get Featured News:**
```bash
curl https://yourdomain.com/api/news/featured?limit=3
```

**Get All News with Pagination:**
```bash
curl https://yourdomain.com/api/news?page=1&per_page=10
```

**Get Specific Article:**
```bash
curl https://yourdomain.com/api/news/new-property-market-trends-for-2024
```

### Programmatic Usage

**Get Latest Published News:**
```php
$news = News::published()->latest('published_at')->limit(5)->get();
```

**Get Featured News:**
```php
$featuredNews = News::published()->featured()->get();
```

**Get News by Slug:**
```php
$article = News::where('slug', $slug)->published()->firstOrFail();
```

## Database Schema

**news table:**
- `id` - Primary key
- `title` - Article title
- `slug` - URL-friendly unique identifier
- `excerpt` - Optional short summary
- `content` - Full article content (supports HTML)
- `is_featured` - Boolean flag for featured articles
- `published_at` - Publication timestamp (null for drafts)
- `author_id` - Foreign key to users table
- `team_id` - Foreign key to teams table
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `deleted_at` - Soft delete timestamp

## Permissions

News management is available to users with access to the Staff Filament panel. Ensure proper role-based access control is configured in your Filament Shield settings.

## Customization

### Changing News Per Page
Edit `app/Http/Livewire/NewsList.php`:
```php
return $query->paginate(12); // Change 12 to your preferred number
```

### Modifying Rich Text Editor Toolbar
Edit `app/Filament/Staff/Resources/News/NewsResource.php`:
```php
RichEditor::make('content')
    ->toolbarButtons([
        'bold',
        'italic',
        // Add or remove toolbar buttons
    ])
```

### Adding Image Support
To add image uploads to news articles, integrate with Spatie Media Library:

1. Add `HasMedia` interface to News model
2. Add image upload field to NewsResource form
3. Update views to display images

## Best Practices

1. **SEO**: Use descriptive titles and excerpts for better search engine visibility
2. **Scheduling**: Schedule news releases for optimal engagement times
3. **Featured Articles**: Limit featured articles to 3-5 most important stories
4. **Content Quality**: Use the excerpt field for better social media sharing
5. **Regular Updates**: Keep news fresh and relevant to maintain user engagement

## Support

For issues or questions about the news feature, please refer to the main project documentation or create an issue in the repository.
