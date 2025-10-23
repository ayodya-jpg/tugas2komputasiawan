pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Clone repository dari GitHub
                git credentialsId: 'tugas2komputasi',
                    url: 'https://github.com/ayodya-jpg/tugas2komputasiawan.git',
                    branch: 'main'
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo "Preparing .env file..."

                    // Hapus file .env lama jika ada
                    bat '''
                        if exist .env del /f .env
                    '''

                    // Salin .env.example jadi .env
                    bat '''
                        copy .env.example .env
                    '''

                    // Ganti nilai di .env menggunakan PowerShell
                    // Ganti DB_HOST, REDIS_HOST, MAIL_HOST, DB_USERNAME, DB_PASSWORD
                    powershell '''
                        (Get-Content .env) -replace "DB_HOST=127.0.0.1", "DB_HOST=db" |
                        ForEach-Object {$_ -replace "REDIS_HOST=127.0.0.1", "REDIS_HOST=redis"} |
                        ForEach-Object {$_ -replace "MAIL_HOST=127.0.0.1", "MAIL_HOST=mailpit"} |
                        ForEach-Object {$_ -replace "DB_USERNAME=root", "DB_USERNAME=laraveluser"} |
                        ForEach-Object {$_ -replace "DB_PASSWORD=", "DB_PASSWORD=password"} |
                        Set-Content .env -Encoding UTF8
                    '''

                    echo "Building Docker images..."
                    bat 'docker compose up -d --build'
                }
            }
        }

        stage('Initialize Application') {
            steps {
                script {
                    echo "Waiting for DB to be ready..."
                    sleep 15

                    echo "Generating App Key..."
                    bat 'docker compose exec app php artisan key:generate'

                    echo "Fixing permissions..."
                    bat 'docker compose exec app sh -c "chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache"'

                    echo "Installing NPM packages..."
                    bat 'docker compose exec app npm install'

                    echo "Building Vite assets..."
                    bat 'docker compose exec app npm run build'

                    echo "Running migrations..."
                    bat 'docker compose exec app php artisan config:clear'
                    bat 'docker compose exec app php artisan migrate --force'
                }
            }
        }

        stage('Clean Up') {
            steps {
                echo "Cleaning up unused Docker images..."
                bat 'docker image prune -f'
            }
        }
    }
}
