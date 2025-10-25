pipeline {
    agent any

    environment {
        COMPOSE_FILE = 'docker-compose.yaml'
        IMAGE_NAME = 'ayodyawasesa/tugas2-app'
        DOCKERHUB_CREDENTIALS = credentials('tugas2komputasi')
    }

    stages {
        stage('Checkout Source Code') {
            steps {
                echo '📦 Mengambil source code dari GitHub...'
                git branch: 'main', credentialsId: 'tugas2komputasi', url: 'https://github.com/ayodya-jpg/tugas2komputasiawan.git'
            }
        }

        stage('Build & Run Containers') {
            steps {
                echo '🚀 Membangun dan menjalankan container...'
                bat '''
                docker compose down --remove-orphans
                docker rm -f project_laravelcc2_app || echo "Tidak ada container lama"
                docker compose build --no-cache
                docker compose up -d
                '''
            }
        }

                stage('Setup Laravel Environment') {
            steps {
                echo '⚙️ Menyiapkan environment Laravel...'
                bat '''
                docker compose exec -T app sh -c "cp .env.example .env || echo ENV sudah ada"
                docker compose exec -T app sh -c "composer install --no-interaction --prefer-dist --optimize-autoloader"
                docker compose exec -T app sh -c "chmod -R 777 storage bootstrap/cache"
                docker compose exec -T app sh -c "php artisan key:generate"
                docker compose exec -T app sh -c "php artisan migrate --force"
                docker compose exec -T app sh -c "php artisan config:cache"
                '''
            }
        }

        stage('Push Docker Image') {
            steps {
                echo '📤 Mengunggah image ke Docker Hub...'
                bat """
                docker login -u %DOCKERHUB_CREDENTIALS_USR% -p %DOCKERHUB_CREDENTIALS_PSW%
                docker tag project_laravelcc2_app %IMAGE_NAME%:latest
                docker push %IMAGE_NAME%:latest
                """
            }
        }

        stage('Health Check') {
            steps {
                bat 'docker compose ps'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline berhasil! Akses aplikasi di: http://localhost:8888'
        }
        failure {
            echo '❌ Pipeline gagal. Cek log Jenkins untuk detail error.'
        }
    }
}
