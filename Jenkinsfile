pipeline {
    agent any // Jalankan di server Jenkins

    stages {
        stage('Checkout') {
            steps {
                // Tarik kode terbaru dari GitHub
                // GANTI 'ID-KREDENSIAL' dan 'URL-REPO'
                git credentialsId: 'ID-KREDENSIAL-GITHUB-ANDA', url: 'URL-REPO-GITHUB-ANDA.git', branch: 'main'
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo "Preparing .env file..."
                    // Hapus file .env lama jika ada
                    sh 'rm -f .env'

                    // Salin file .env.example sebagai .env baru
                    sh 'cp .env.example .env'

                    // Ganti DB_HOST di .env agar menunjuk ke service 'db'
                    sh "sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env"

                    // Ganti DB_HOST untuk Redis dan Mail
                    sh "sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/g' .env"
                    sh "sed -i 's/MAIL_HOST=127.0.0.1/MAIL_HOST=mailpit/g' .env"

                    // Set DB_USERNAME dan DB_PASSWORD (sesuai perbaikan kita)
                    sh "sed -i 's/DB_USERNAME=root/DB_USERNAME=laraveluser/g' .env"
                    sh "sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/g' .env"

                    echo "Building Docker images..."
                    // Jalankan semua service (termasuk build)
                    sh 'docker compose up -d --build'
                }
            }
        }

        stage('Initialize Application') {
            steps {
                script {
                    echo "Waiting for DB to be ready..."
                    // Beri waktu 15 detik agar MySQL siap
                    sleep 15

                    echo "Generating App Key..."
                    sh 'docker compose exec app php artisan key:generate'

                    echo "Fixing permissions..."
                    // Perbaiki izin folder (Masalah #1)
                    sh 'docker compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache'

                    echo "Installing NPM packages..."
                    // Install dependensi frontend (Masalah #2)
                    sh 'docker compose exec app npm install'

                    echo "Building Vite assets..."
                    // Build aset frontend (Masalah #2)
                    sh 'docker compose exec app npm run build'

                    echo "Running migrations..."
                    // Jalankan migrasi database
                    sh 'docker compose exec app php artisan config:clear'
                    sh 'docker compose exec app php artisan migrate --force'
                }
            }
        }

        stage('Clean Up') {
            steps {
                sh 'docker image prune -f'
            }
        }
    }
}
