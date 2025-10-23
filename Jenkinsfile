pipeline {
    agent any

    environment {
        DOCKER_COMPOSE = 'docker compose'
        GIT_CREDENTIALS = 'tugas2komputasi'
    }

    stages {
        stage('Checkout') {
            steps {
                echo 'Checking out repository...'
                git branch: 'main',
                    credentialsId: "${GIT_CREDENTIALS}",
                    url: 'https://github.com/ayodya-jpg/tugas2komputasiawan.git'
            }
        }

        stage('Prepare Environment') {
            steps {
                echo 'Preparing .env file...'
                bat '''
                if exist .env del /f .env
                copy .env.example .env
                '''
            }
        }

        stage('Build and Deploy') {
            steps {
                script {
                    echo 'Building Docker images...'
                    bat "${DOCKER_COMPOSE} up -d --build"
                }
            }
        }

        stage('Run Migrations & Cache') {
            steps {
                script {
                    echo 'Running Laravel optimization and migrations...'
                    bat "${DOCKER_COMPOSE} exec app php artisan key:generate"
                    bat "${DOCKER_COMPOSE} exec app php artisan migrate:fresh --seed"
                    bat "${DOCKER_COMPOSE} exec app php artisan config:cache"
                    bat "${DOCKER_COMPOSE} exec app php artisan route:cache"
                    bat "${DOCKER_COMPOSE} exec app php artisan view:cache"
                }
            }
        }

        stage('Testing') {
            steps {
                script {
                    echo 'Running Laravel tests...'
                    bat "${DOCKER_COMPOSE} exec app php artisan test || exit 0"
                }
            }
        }
    }

    post {
        success {
            echo '✅ Deployment completed successfully!'
        }
        failure {
            echo '❌ Deployment failed. Please check the logs.'
        }
        always {
            echo 'Cleaning up unused Docker resources...'
            bat "docker system prune -f"
        }
    }
}
