<?php

namespace App\Livewire\Admin;

use App\Models\Guide;
use App\Models\GuideCategory;
use App\Models\Faq;
use App\Models\Link;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class Import extends AdminComponent
{
    public $importingGuides = false;
    public $importProgress = 0;
    public $importStatus = '';
    public $showConfirmation = false;
    public $importType = '';
    public $existingContent = false;

    protected function convertMarkdownToTrixHtml($markdown)
    {
        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = $converter->convert($markdown);

        // Convert the CommonMark HTML to Trix-compatible HTML
        $trixHtml = str_replace(
            ['<h1>', '<h2>', '<h3>', '<p>', '<ul>', '<ol>', '<li>'],
            [
                '<h1 class="text-2xl font-bold text-white mb-4">',
                '<h2 class="text-xl font-bold text-white mt-6 mb-3">',
                '<h3 class="text-lg font-bold text-white mt-4 mb-2">',
                '<p class="text-gray-300 mb-4">',
                '<ul class="list-disc list-inside text-gray-300 mb-4 space-y-2">',
                '<ol class="list-decimal list-inside text-gray-300 mb-4 space-y-2">',
                '<li class="text-gray-300">'
            ],
            $html
        );

        // Wrap the content in a div with Trix styling
        return '<div class="trix-content text-gray-300">' . $trixHtml . '</div>';
    }

    protected function checkExistingContent($type)
    {
        switch ($type) {
            case 'basic_guides':
                return GuideCategory::where('name', $this->plexBasicGuides['category']['name'])->exists();
            default:
                return false;
        }
    }

    public function confirmImport($type)
    {
        $this->importType = $type;
        $this->existingContent = $this->checkExistingContent($type);
        $this->showConfirmation = true;
    }

    public function cancelImport()
    {
        $this->showConfirmation = false;
        $this->importType = '';
        $this->existingContent = false;
    }

    public function executeImport()
    {
        switch ($this->importType) {
            case 'basic_guides':
                $this->importPlexBasicGuides();
                break;
        }

        $this->showConfirmation = false;
        $this->importType = '';
    }

    protected $plexBasicGuides = [
        'category' => [
            'name' => 'Getting Started with Plex',
            'order' => 1,
            'guides' => [
                [
                    'title' => 'Welcome to Plex',
                    'order' => 1,
                    'content' => "# Welcome to Plex\n\nWelcome to Plex! This guide will help you get started with watching your favorite content. Plex is a streaming platform that lets you watch movies, TV shows, and more from any device.\n\n## What is Plex?\n\nPlex is like having your own personal Netflix. It organizes movies, TV shows, music, and photos in a beautiful interface that's easy to use. You can watch on your TV, phone, tablet, or computer.\n\n## Key Features\n- Watch anywhere, anytime\n- Continue watching where you left off\n- Create your own watchlist\n- Rate and review content\n- Get personalized recommendations"
                ],
                [
                    'title' => 'Getting Started',
                    'order' => 2,
                    'content' => "# Getting Started with Plex\n\n## Creating Your Account\n1. Go to plex.tv/sign-up\n2. Create an account with your email\n3. Verify your email address\n\n## Signing In\n1. Visit app.plex.tv or download the Plex app\n2. Click 'Sign In'\n3. Enter your email and password\n\n## Accepting Server Invitation\nWhen someone shares their Plex server with you:\n1. You'll receive an email invitation\n2. Click the link in the email\n3. Sign in to your Plex account\n4. Accept the invitation\n\nNow you can access all the shared content!"
                ],
                [
                    'title' => 'Using the Plex Interface',
                    'order' => 3,
                    'content' => "# Using the Plex Interface\n\n## Home Screen\nThe home screen shows:\n- Recently added content\n- Continue watching\n- Recommended for you\n- Popular movies and shows\n\n## Finding Content\n1. Use the search bar at the top\n2. Browse by category (Movies, TV Shows, etc.)\n3. Use filters to sort by:\n   - Recently added\n   - Title\n   - Release date\n   - Rating\n\n## Playing Content\n1. Click on any title\n2. View details, ratings, and description\n3. Click 'Play' to start watching\n4. Use player controls to:\n   - Pause/Play\n   - Adjust volume\n   - Enable subtitles\n   - Change audio tracks"
                ],
                [
                    'title' => 'Watching on Different Devices',
                    'order' => 4,
                    'content' => "# Watching Plex on Different Devices\n\n## Supported Devices\n- Smart TVs (Samsung, LG, etc.)\n- Streaming devices (Roku, Fire TV, Apple TV)\n- Mobile devices (iOS and Android)\n- Web browsers\n- Gaming consoles\n\n## Setting Up Devices\n1. Download the Plex app from your device's app store\n2. Sign in with your Plex account\n3. Your shared libraries will appear automatically\n\n## Quality Settings\nPlex automatically adjusts video quality based on:\n- Your internet speed\n- Device capabilities\n- Server settings\n\nYou can manually adjust quality in the player settings if needed."
                ],
                [
                    'title' => 'Managing Your Experience',
                    'order' => 5,
                    'content' => "# Managing Your Plex Experience\n\n## Watchlist\nKeep track of what you want to watch:\n1. Click the bookmark icon on any title\n2. Find your watchlist in the sidebar\n3. Remove items when finished\n\n## Continue Watching\n- Plex remembers where you stopped\n- Resume from any device\n- Mark shows as watched/unwatched\n\n## Customizing Your Experience\n1. Set preferred subtitle language\n2. Choose default audio language\n3. Adjust streaming quality\n4. Customize home screen layout\n\n## Getting Help\nIf you need assistance:\n1. Check the FAQ section\n2. Contact the server owner\n3. Visit plex.tv/support"
                ]
            ]
        ]
    ];

    protected function importPlexBasicGuides()
    {
        $this->importingGuides = true;
        $this->importProgress = 0;
        $this->importStatus = 'Starting import...';

        try {
            DB::beginTransaction();

            // Create or find the category
            $category = GuideCategory::firstOrCreate(
                ['name' => $this->plexBasicGuides['category']['name']],
                ['order' => $this->plexBasicGuides['category']['order']]
            );

            $totalGuides = count($this->plexBasicGuides['category']['guides']);
            $completed = 0;

            foreach ($this->plexBasicGuides['category']['guides'] as $guideData) {
                $this->importStatus = "Importing guide: {$guideData['title']}";
                
                Guide::updateOrCreate(
                    [
                        'title' => $guideData['title'],
                        'guide_category_id' => $category->id
                    ],
                    [
                        'slug' => Str::slug($guideData['title']),
                        'content' => $this->convertMarkdownToTrixHtml($guideData['content']),
                        'order' => $guideData['order']
                    ]
                );

                $completed++;
                $this->importProgress = ($completed / $totalGuides) * 100;
            }

            DB::commit();
            
            $this->importStatus = 'Import completed successfully!';
            session()->flash('message', 'Basic Plex guides imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->importStatus = 'Import failed: ' . $e->getMessage();
            session()->flash('error', 'Failed to import guides: ' . $e->getMessage());
        }

        $this->importingGuides = false;
    }

    protected $serverSpecificGuides = [
        'category' => [
            'name' => 'Server-Specific Guides',
            'description' => 'Essential guides for using and understanding our Plex server',
            'order' => 1
        ],
        'guides' => [
            [
                'title' => 'How to Request Movies or TV Shows',
                'content' => "# Requesting Content on Our Plex Server

## Using Overseerr
1. Log in to our Overseerr instance
2. Search for the movie or TV show you want
3. Click the 'Request' button
4. Fill in any additional notes if needed
5. Submit your request

## Request Guidelines
- Check if the content already exists before requesting
- Make sure the content is available for streaming
- Adult content is not permitted
- Cam recordings or low-quality releases are not accepted

## Request Status
- Pending: Your request is being reviewed
- Approved: Content will be added soon
- Denied: Request cannot be fulfilled (reason will be provided)

## Processing Times
- New releases: 1-3 days after high-quality release
- Older content: 2-5 days
- TV Shows: Episodes added as they become available",
                'order' => 1
            ],
            [
                'title' => 'Understanding Server Rules',
                'content' => "# Server Rules and Guidelines

## Access Policies
- Do not share your account credentials
- One stream per user unless otherwise arranged
- Transcoding is limited to maintain server performance
- Direct Play is preferred when possible

## Data Usage
- No bandwidth caps currently enforced
- Excessive usage may be monitored
- Multiple simultaneous streams may be limited

## Server Maintenance
- Regular maintenance: Sundays 2-4 AM EST
- Emergency maintenance may occur without notice
- Updates posted in Discord/Telegram
- Planned outages announced 48 hours in advance

## User Expectations
- Report any issues promptly
- Respect server resources
- Keep client apps updated
- Follow naming conventions for requests",
                'order' => 2
            ],
            [
                'title' => 'How to Report Issues',
                'content' => "# Reporting Server Issues

## Common Issues to Report
- Missing content
- Playback problems
- Audio/subtitle issues
- Metadata errors

## Reporting Process
1. Check server status first
2. Verify the issue isn't client-side
3. Gather relevant details:
   - Content name
   - Time of issue
   - Device/player used
   - Error messages
4. Submit through appropriate channel

## Contact Methods
- Discord: #support-channel
- Email: support@domain.com
- Web form: [Support Portal]

## Response Times
- Critical issues: 1-2 hours
- General issues: 24 hours
- Enhancement requests: 1 week

## Follow-up
- Keep ticket/thread updated
- Mark as resolved when fixed
- Update if issue returns",
                'order' => 3
            ],
            [
                'title' => 'Server Features Overview',
                'content' => "# Server Features and Capabilities

## Library Organization
- Movies sorted by genre, year, and rating
- TV Shows organized by network and status
- Custom collections for franchises
- Special categories for new releases

## Enhanced Features
- Hardware-accelerated transcoding
- Automated subtitle downloads
- Custom metadata and artwork
- Watch progress sync across devices

## Available Tools
- Overseerr for requests
- Tautulli for statistics
- Discord bot integration
- Mobile app support

## Special Libraries
- 4K HDR content
- Documentary collection
- Anime section
- Kids content zone

## Plugins & Add-ons
- Sub-Zero for subtitles
- Plex Meta Manager
- Custom themes
- Enhanced metadata agents",
                'order' => 4
            ]
        ]
    ];

    public function importServerSpecificGuides()
    {
        $this->importingGuides = true;
        $this->importProgress = 0;
        $this->importStatus = 'Starting import...';

        try {
            DB::beginTransaction();

            // Create or find the category
            $category = GuideCategory::updateOrCreate(
                ['name' => $this->serverSpecificGuides['category']['name']],
                [
                    'description' => $this->serverSpecificGuides['category']['description'],
                    'order' => $this->serverSpecificGuides['category']['order']
                ]
            );

            $totalGuides = count($this->serverSpecificGuides['guides']);
            $completed = 0;

            foreach ($this->serverSpecificGuides['guides'] as $guideData) {
                $this->importStatus = "Importing guide: {$guideData['title']}";
                
                Guide::updateOrCreate(
                    [
                        'title' => $guideData['title'],
                        'guide_category_id' => $category->id
                    ],
                    [
                        'slug' => Str::slug($guideData['title']),
                        'content' => $this->convertMarkdownToTrixHtml($guideData['content']),
                        'order' => $guideData['order']
                    ]
                );

                $completed++;
                $this->importProgress = ($completed / $totalGuides) * 100;
            }

            DB::commit();
            
            $this->importStatus = 'Import completed successfully!';
            session()->flash('message', 'Server-specific guides imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->importStatus = 'Import failed: ' . $e->getMessage();
            session()->flash('error', 'Failed to import server-specific guides: ' . $e->getMessage());
        }

        $this->importingGuides = false;
    }

    public function render(): View
    {
        return view('livewire.admin.import');
    }
} 