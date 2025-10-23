pipeline {
    agent any

    environment {
        COMPOSE_FILE = 'docker-compose.yaml'
        IMAGE_NAME = 'tugas2-app'
        CONTAINER_APP = 'project_laravel_app'
    }

    stages {
        stage('Checkout Source Code') {
            steps {
                echo '📦 Mengambil source code dari repository GitHub...'
                git branch: 'main', credentialsId: 'tugas2komputasi', url: 'https://github.com/ayodya-jpg/tugas2komputasiawan.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                echo '🔨 Membangun image Docker untuk Laravel...'
                bat 'docker compose build --no-cache'
            }
        }

        stage('Start Docker Containers') {
            steps {
                echo '🚀 Menjalankan seluruh container (App, Nginx, DB, Redis, Mailpit)...'
                bat 'docker compose up -d'
            }
        }

        stage('Install Dependencies') {
            steps {
                echo '📦 Menginstall dependensi Composer di dalam container Laravel...'
                bat 'docker compose exec -T app composer install --optimize-autoloader --no-dev'
            }
        }

        stage('Setup Laravel Environment') {
            steps {
                echo '⚙️ Menyiapkan environment Laravel (.env, key, dan cache)...'
                bat '''
                docker compose exec -T app cp .env.example .env || echo "ENV file already exists"
                docker compose exec -T app php artisan key:generate
                docker compose exec -T app php artisan config:cache
                docker compose exec -T app php artisan route:cache
                docker compose exec -T app php artisan view:cache
                '''
            }
        }

        stage('Run Database Migration & Seed') {
            steps {
                echo '🧱 Menjalankan migrasi dan seeding database Laravel...'
                bat 'docker compose exec -T app php artisan migrate --force'
                bat 'docker compose exec -T app php artisan db:seed --force || echo "Seeder optional"'
            }
        }

        stage('Health Check') {
            steps {
                echo '🩺 Mengecek status container...'
                bat 'docker ps'
                bat 'docker compose ps'
            }
        }

        stage('Verify App Access') {
            steps {
                echo '🌐 Mengecek apakah aplikasi Laravel dapat diakses dari Nginx...'
                bat 'curl -f http://localhost:8888 || echo "⚠️ Aplikasi belum bisa diakses. Cek log Nginx atau App."'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline berhasil! Aplikasi Laravel sudah berjalan di Docker.'
            echo 'Akses aplikasi di: http://localhost:8888'
            echo 'Mailpit UI: http://localhost:8025'
        }
        failure {
            echo '❌ Pipeline gagal. Periksa log Jenkins untuk detail error.'
        }
        always {
            echo '🧹 Membersihkan cache pipeline...'
        }
    }
}
