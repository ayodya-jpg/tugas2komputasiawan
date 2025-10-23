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

        stage('Build & Run Containers') {
            steps {
                echo '🚀 Membangun image dan menjalankan seluruh container...'
                bat '''
                docker compose down
                docker compose build --no-cache
                docker compose up -d
                '''
            }
        }

        stage('Setup Laravel Environment') {
            steps {
                echo '⚙️ Menyiapkan environment Laravel...'
                bat '''
                docker compose exec -T app cp .env.example .env || echo "ENV sudah ada"
                docker compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader
                docker compose exec -T app php artisan key:generate
                docker compose exec -T app php artisan migrate --force
                docker compose exec -T app php artisan config:cache
                '''
            }
        }

        stage('Health Check') {
            steps {
                echo '🩺 Mengecek status container...'
                bat 'docker compose ps'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline berhasil! Aplikasi Laravel sudah berjalan.'
            echo 'Akses di: http://localhost:8888'
        }
        failure {
            echo '❌ Pipeline gagal. Periksa log Jenkins untuk detail error.'
        }
    }
}
