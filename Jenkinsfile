pipeline {
    environment {
    registry = "sureshdrupal/node-app"
    registryCredential = 'dockerhub'
    dockerImage = ''
    }

    agent any
    stages {
            stage('Cloning our Git') {
                steps {
                git 'git@github.com:sureshabi7/custom-block.git'
                }
            }

            stage('Building Docker Image') {
                steps {
                    script {
                        dockerImage = docker.build registry + ":$BUILD_NUMBER"
                    }
                }
            }

            stage('Deploying Docker Image to Dockerhub') {
                steps {
                    script {
                        docker.withRegistry('', registryCredential) {
                        dockerImage.push()
                        }
                    }
                }
            }
            stage('Remove running container with old code'){
                steps {
                    script {
                        sh "docker rm -f \$(docker ps -a -f name=node-app -q) || true"
                    }
                } 
            }
            stage('Deploy Docker Image with new changes'){
                steps {
                    script {
                        //start container with the remote image
                        sh "docker run --name node-app -d -p 2222:2222 ${registry}:${env.BUILD_NUMBER}"
                    }
                }
            }
            stage('Remove old images') {
                steps {
                    script {
                       // remove docker old images
                        sh("docker rmi ${registry}:${env.BUILD_NUMBER} -f")
                    }
                }
            }
        }
    }