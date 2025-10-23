pipeline {
    agent any // Jalankan di server Jenkins

    stages {
        stage('Checkout') {
            steps {
                // Tarik kode terbaru dari GitHub
                // GANTI 'ID-KREDENSIAL' dan 'URL-REPO'
                git credentialsId: 'tugas2komputasi', url: 'https://github.com/ayodya-jpg/tugas2komputasiawan.git', branch: 'main'
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo "Preparing .env file..."
                    // Hapus file .env lama jika ada
                    bat 'rm -f .env'

                    // Salin file .env.example sebagai .env baru
                    bat 'cp .env.example .env'

                    // Ganti DB_HOST di .env agar menunjuk ke service 'db'
                    bat "sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env"

                    // Ganti DB_HOST untuk Redis dan Mail
                    bat "sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/g' .env"
                    bat "sed -i 's/MAIL_HOST=127.0.0.1/MAIL_HOST=mailpit/g' .env"

                    // Set DB_USERNAME dan DB_PASSWORD (sesuai perbaikan kita)
                    bat "sed -i 's/DB_USERNAME=root/DB_USERNAME=laraveluser/g' .env"
                    bat "sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/g' .env"

                    echo "Building Docker images..."
                    // Jalankan semua service (termasuk build)
                    bat 'docker compose up -d --build'
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
                    bat 'docker compose exec app php artisan key:generate'

                    echo "Fixing permissions..."
                    // Perbaiki izin folder (Masalah #1)
                    bat 'docker compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache'

                    echo "Installing NPM packages..."
                    // Install dependensi frontend (Masalah #2)
                    bat 'docker compose exec app npm install'

                    echo "Building Vite assets..."
                    // Build aset frontend (Masalah #2)
                    bat 'docker compose exec app npm run build'

                    echo "Running migrations..."
                    // Jalankan migrasi database
                    bat 'docker compose exec app php artisan config:clear'
                    bat 'docker compose exec app php artisan migrate --force'
                }
            }
        }

        stage('Clean Up') {
            steps {
                bat 'docker image prune -f'
            }
        }
    }
}
