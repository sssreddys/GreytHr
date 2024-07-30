pipeline {
    agent any
    environment {
        DEPLOY_DIR = 'C:\\xampp\\htdocs\\deploye_server'
        GIT_URL = 'https://github.com/sssreddys/GreytHr.git'
        GIT_BRANCH = 'main'
        GIT_PATH = 'C:\\Users\\SivaKumarSaragada\\AppData\\Local\\Programs\\Git\\cmd\\git.exe'
    }
    stages {
        stage('Create Directory') {
            steps {
                bat """
                if not exist "${DEPLOY_DIR}" mkdir "${DEPLOY_DIR}"
                """
            }
        }
        stage('Check and Update/Clone Repository') {
            steps {
                script {
                    def gitDirExists = fileExists("${DEPLOY_DIR}\\.git")
                    if (gitDirExists) {
                        dir("${DEPLOY_DIR}") {
                            bat """
                            "${env.GIT_PATH}" fetch --all
                            "${env.GIT_PATH}" reset --hard origin/${GIT_BRANCH}
                            """
                        }
                    } else {
                        dir("${DEPLOY_DIR}") {
                            def dirNotEmpty = bat(script: "if exist * (echo 1)", returnStatus: true) == 0
                            if (dirNotEmpty) {
                                error "Non-empty directory without repository found. Aborting."
                            } else {
                                bat """
                                "${env.GIT_PATH}" clone -b ${GIT_BRANCH} ${GIT_URL} .
                                """
                            }
                        }
                    }
                }
            }
        }
        stage('Prepare .env file') {
            steps {
                script {
                    def envFileExists = fileExists("${DEPLOY_DIR}\\.env")
                    if (!envFileExists) {
                        bat """
                        copy ${DEPLOY_DIR}\\.env.example ${DEPLOY_DIR}\\.env
                        """
                        def dbConfigExists = bat(script: "findstr /m \"DB_DATABASE\" ${DEPLOY_DIR}\\.env", returnStatus: true) == 0
                        if (!dbConfigExists) {
                            // Add your database configuration details here
                            bat """
                            echo DB_CONNECTION=mysql >> ${DEPLOY_DIR}\\.env
                            echo DB_HOST=127.0.0.1 >> ${DEPLOY_DIR}\\.env
                            echo DB_PORT=3306 >> ${DEPLOY_DIR}\\.env
                            echo DB_DATABASE=your_database_name >> ${DEPLOY_DIR}\\.env
                            echo DB_USERNAME=your_database_username >> ${DEPLOY_DIR}\\.env
                            echo DB_PASSWORD=your_database_password >> ${DEPLOY_DIR}\\.env
                            """
                        }
                    }
                }
            }
        }
        stage('Install dependencies and generate APP_KEY') {
            steps {
                dir("${DEPLOY_DIR}") {
                    bat """
                    composer install
                    php artisan key:generate
                    """
                }
            }
        }
        stage('Clear caches and run tests') {
            steps {
                dir("${DEPLOY_DIR}") {
                    bat """
                    php artisan config:clear
                    php artisan cache:clear
                    php artisan route:clear
                    php artisan view:clear
                    php artisan test
                    """
                }
            }
        }
        stage('Run server') {
            steps {
                dir("${DEPLOY_DIR}") {
                    bat "php artisan serve"
                }
            }
        }
    }
    post {
        always {
            cleanWs()
        }
    }
}
