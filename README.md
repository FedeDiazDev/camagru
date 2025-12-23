# 📸 Camagru

> A minimalist social network for photography lovers. Inspire, capture, and share.

[![PHP](https://img.shields.io/badge/PHP-Vanilla-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![Nginx](https://img.shields.io/badge/Nginx-Server-009639?style=for-the-badge&logo=nginx&logoColor=white)](https://nginx.org/)
[![MariaDB](https://img.shields.io/badge/MariaDB-Database-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)

---

## 🚀 About the Project

**Camagru** is a full-stack web application developed as part of the 42 curriculum. The main objective is to build an Instagram-like platform from scratch, **without using PHP frameworks**, to deeply understand web development fundamentals, security, and MVC architecture.

Here you can capture moments with your webcam, apply fun real-time filters, and share your creations with the world.

## ✨ Key Features

### 📷 Creative Studio
- **Webcam Integration**: Take photos directly from your browser.
- **Image Upload**: Have a cool photo on your disk? Upload it!
- **Filter Overlay**: Add stickers and filters to your images before saving.

### 🖼️ Social Gallery
- **Public Feed**: Explore other users' creations.
- **Interaction**: Give ❤️ **Likes** and leave **comments** on photos that inspire you.
- **Infinite Pagination**: Smooth navigation through all posts.

### 🔐 Security & Users
- **Secure Registration**: Hashed passwords and robust validation.
- **Email Verification**: Account activation via unique link.
- **Password Recovery**: Automatic reset system via email.
- **Profile Management**: Update your details and notification preferences.

---

## 🛠️ Tech Stack

This project has been built with love and pure code:

- **Backend**: PHP (Native, no frameworks).
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla).
- **Database**: MariaDB / MySQL.
- **Web Server**: Nginx.
- **Containerization**: Docker & Docker Compose.

---

## 📦 Installation and Deployment

The easiest way to run Camagru is using Docker.

### Prerequisites
- Docker and Docker Compose installed on your system.

### Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/camagru.git
   cd camagru
   ```

2. **Configure environment**:
   Make sure to have the `.env` file configured (a base example is included).

3. **Start containers**:
   ```bash
   docker-compose up -d --build
   ```

4. **Ready!**:
   Open your browser and visit:
   👉 `http://localhost:8081` (or the port configured in your docker-compose).

---

## 📂 Project Structure

The code follows an **MVC (Model-View-Controller)** architecture to maintain order and scalability:

```
/camagru
├── app/
│   ├── config/      # Database configuration
│   ├── controllers/ # Business logic
│   ├── models/      # DB interaction
│   └── views/       # User Interface (HTML/PHP)
├── config/          # Docker/Nginx configurations
├── public/          # Assets (CSS, JS, Images)
└── docker-compose.yml
```

---

## 📝 Developer Notes

This project is a learning exercise. Special emphasis has been placed on **security** (preventing SQL injections, XSS, CSRF) and **clean structure** without depending on external libraries for core logic.

Enjoy capturing moments! 📸✨
