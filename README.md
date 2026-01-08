# Inventory Management App
# Inventory Management System

A feature-rich, web-based inventory management application built with **Symfony 7** and **MySQL**. This system allows users to create custom inventories with configurable fields and unique ID generation formats, making it adaptable to a wide variety of use cases.

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-7.4-000000?logo=symfony&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-Proprietary-red)

---

## ✨ Key Features

### 🗂️ Custom Inventories
- Create inventories with **Title**, **Description** (Markdown supported), **Category**, **Image**, and **Tags**.
- Inventories can be **Public** (anyone can add items) or **Private** (only invited users).
- **Auto-save** functionality saves progress every 7-10 seconds.

### 🔧 Custom Fields (Killer Feature #1)
Define up to **15 custom fields** per inventory (max 3 per type):
| Field Type | Description |
|------------|-------------|
| Single-line Text | Short text input |
| Multi-line Text | Long text with Markdown support |
| Numeric | Integer or floating-point numbers |
| Boolean | Checkbox (Yes/No) |
| Document/Image Link | URL to external resource |

- **Drag-and-drop** reordering of fields.
- Custom titles and tooltip descriptions.
- Toggle visibility in item table view.

### 🔢 Custom Inventory IDs (Killer Feature #2)
Generate unique, customizable IDs for items using a **drag-and-drop builder**:
| Element | Example Output |
|---------|----------------|
| Static Text | `INV-`, `ITEM-` |
| 6-Digit Random | `482910` |
| 9-Digit Random | `938271564` |
| 20-bit Random | `0-1048575` |
| 32-bit Random | `0-4294967295` |
| GUID | `a1b2c3d4-...` |
| Date/Time | `2026-01-08` |
| Sequence | `001`, `002`, ... |

- IDs are **unique per inventory** (enforced by database index).
- Editable with format validation.
- Real-time preview with help popovers.

### 👥 User Management & Authentication
- **Social Login** via Google and Facebook OAuth 2.0.
- Role-based access control:
  - **Guest**: Read-only access to public inventories.
  - **User**: Create inventories, add items (if permitted), like items, comment.
  - **Inventory Owner**: Full control over their inventories.
  - **Admin**: Full system access, user management.
- User profile page showing owned inventories and accessible inventories.

### 🛡️ Admin Panel
- View, block/unblock, and delete users.
- Grant or revoke admin roles.
- Admins can remove their own admin status.

### 💬 Discussions & Interactions
- **Real-time comments** on inventories (2-5 second refresh).
- Markdown-supported posts.
- **Like system**: One like per user per item.

### 🔍 Search & Discovery
- **Global full-text search** available on every page.
- Homepage displays:
  - Latest inventories
  - Top 5 popular inventories (by item count)
  - Tag cloud for quick filtering

### 🎨 UI/UX
- **Responsive design** for mobile, tablet, and desktop.
- **Two themes**: Light and Dark (user-selectable).
- **Multi-language**: English + additional language support.
- Modern typography (Inter, Roboto).
- Smooth transitions and micro-animations.

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 8.2+, Symfony 7.4 |
| **Database** | MySQL 8.0, Doctrine ORM |
| **Frontend** | Twig, Bootstrap 5, Stimulus |
| **Authentication** | OAuth 2.0 (Google, Facebook) |
| **Real-time** | Symfony UX Turbo, Polling |
| **Assets** | Symfony Asset Mapper |

### Notable Packages
- `knpuniversity/oauth2-client-bundle` - OAuth integration
- `league/oauth2-google` & `league/oauth2-facebook` - Social providers
- `ramsey/uuid` - UUID generation
- `symfony/ux-turbo` - Real-time updates

---

## 📁 Project Structure

```
inventory-app/
├── .htaccess              # Root redirect to public/
├── public/                # Web root (document root)
│   ├── index.php          # Symfony front controller
│   ├── .htaccess          # URL rewriting
│   └── assets/            # Compiled assets
└── project/               # Symfony application
    ├── bin/               # Console commands
    ├── config/            # Configuration files
    ├── migrations/        # Database migrations
    ├── src/               # PHP source code
    │   ├── Controller/    # HTTP controllers
    │   ├── Entity/        # Doctrine entities
    │   ├── Repository/    # Database queries
    │   ├── Security/      # Authentication logic
    │   └── ...
    ├── templates/         # Twig templates
    ├── translations/      # i18n files
    ├── var/               # Cache, logs
    ├── vendor/            # Composer dependencies
    ├── .env               # Environment configuration
    └── composer.json      # PHP dependencies
```

---

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js (for asset building, optional)

### Local Development

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/inventory-app.git
   cd inventory-app/project
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env .env.local
   ```
   Edit `.env.local` and set:
   ```env
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/inventory_app"
   GOOGLE_CLIENT_ID="your-google-client-id"
   GOOGLE_CLIENT_SECRET="your-google-client-secret"
   ```

4. **Create database and run migrations**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Start the development server**
   ```bash
   symfony server:start
   # Or use PHP's built-in server:
   php -S localhost:8000 -t ../public
   ```

6. **Visit** `http://localhost:8000`

### Production Deployment (Shared Hosting)

For hosts like InfinityFree without SSH access:

1. Upload `public/` and `project/` folders to your `htdocs/` directory.
2. Upload the root `.htaccess` file to `htdocs/`.
3. Create a `vendor.zip` of the `project/vendor/` folder and upload it.
4. Use the provided `public/unzipper.php` to extract dependencies.
5. Update `project/.env` with production database credentials.
6. Configure Google OAuth with the correct redirect URI:
   ```
   https://your-domain.com/connect/google/check
   ```

---

## ⚙️ Configuration

### Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_ENV` | `dev` or `prod` |
| `APP_SECRET` | Application secret key |
| `DATABASE_URL` | MySQL connection string |
| `GOOGLE_CLIENT_ID` | Google OAuth Client ID |
| `GOOGLE_CLIENT_SECRET` | Google OAuth Client Secret |
| `MAILER_DSN` | Email configuration (optional) |

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/apis/credentials).
2. Create OAuth 2.0 credentials.
3. Add authorized redirect URI:
   ```
   https://your-domain.com/connect/google/check
   ```
4. Copy Client ID and Secret to `.env`.

---

## 📊 Database Schema

```
┌──────────────┐       ┌───────────────┐
│     User     │──────<│   Inventory   │
└──────────────┘       └───────────────┘
       │                      │
       │                      │
       ▼                      ▼
┌──────────────┐       ┌──────────────┐
│     Like     │       │     Item     │
└──────────────┘       └──────────────┘
       │                      │
       └──────────────────────┘
                │
                ▼
         ┌──────────────┐
         │   Comment    │
         └──────────────┘
```

Key constraints:
- Composite unique index on `(inventory_id, custom_id)` for item uniqueness.
- Optimistic locking via version fields.

---

## 🔒 Security Features

- **OAuth 2.0** authentication (no password storage).
- **CSRF protection** on all forms.
- **Role-based access control** (RBAC).
- **Input validation** and sanitization.
- **Optimistic locking** for concurrent edits.

---

## 📝 License

This project is proprietary software. All rights reserved.

---

## 🤝 Contributing

This is a private project. Contributions are not currently accepted.

---

## 📧 Contact

For questions or support, please contact the repository owner.
