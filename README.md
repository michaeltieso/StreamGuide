# StreamGuide

StreamGuide is an open-source Laravel-based web application designed for Plex server owners to provide key information about their servers to users in a clear, organized, and user-friendly manner. Built with Laravel Jetstream, Livewire, and TailwindCSS, it offers a modern, responsive interface for managing and displaying server information.

## Features

- **Server Information**: Display critical server details and configurations
- **Dynamic Guide System**: Create and organize guides for users
- **FAQ Management**: Easy-to-use FAQ system with categories
- **Dark Mode Interface**: Clean, modern dark theme for comfortable viewing
- **Mobile Responsive**: Fully responsive design for all devices

## Quick Start with Docker

### Prerequisites
- Docker
- Docker Compose

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/streamguide.git
cd streamguide
```

2. **Start the container**
```bash
docker compose up -d
```

The application will be available at `http://localhost:9980`

### Environment Variables

The application uses a flexible environment variable system:

1. **Default Configuration**: The `docker-compose.yml` file contains default values:
```yaml
environment:
  - APP_NAME=${APP_NAME:-StreamGuide}
  - APP_ENV=${APP_ENV:-local}
  - APP_DEBUG=${APP_DEBUG:-true}
  - APP_URL=${APP_URL:-http://localhost:9980}
  - TMDB_API_KEY=${TMDB_API_KEY:-your_default_key}
```

2. **Override Options**:
   - Use system environment variables
   - Create a `.env` file
   - Or use the defaults from docker-compose.yml

The application will automatically generate its configuration based on these values. No manual `.env` file creation is needed unless you want to customize beyond the defaults.

Key variables that can be configured:

```env
# App Configuration
APP_NAME=StreamGuide
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:9980

# TMDB API (for backdrop images)
TMDB_API_KEY=your_api_key_here

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/storage/app/database.sqlite
```

### Docker Volume Paths

The application uses two persistent volumes:
- `/var/www/html/storage`: Includes the database and and uploaded files.

### Docker Commands

```bash
# Start the application
docker compose up -d

# View logs
docker compose logs -f

# Stop the application
docker compose down

# Rebuild the container
docker compose up -d --build
```

## Usage

1. Access the application at `http://localhost:9980`
2. Log in with your credentials
3. Start creating guides and organizing server information
4. Share the URL with your Plex users

## Customization

The application can be customized through the admin interface:
- Create and organize guides
- Manage FAQ categories and entries
- Update server information
- Customize appearance settings

## Updating

To update the application:

```bash
git pull
docker compose up -d --build
```

## Support

If you encounter any issues or have questions:
- Open an issue on GitHub
- Check the existing issues for solutions
- Review the documentation in the guides section

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the MIT license.
