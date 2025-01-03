1. Start Docker
Make sure Docker is installed and running on your system before proceeding.

2. Copy the Environment File
After installing the project, copy the .env.example file to .env using the following command:
cp .env.example .env

3. Pull Required Docker Images
Before running docker-compose, you may need to pull some Docker images. For example, to pull the ubuntu:20.04 image, run:

docker pull ubuntu:20.04

4. Ensure Port 8000 Is Available
Before running the project, ensure that nothing is already using port 8000. If another service is using this port, stop or reconfigure it to avoid conflicts.

5. Start the Project
To start the project, run the following command, which will build the containers and start them in detached mode (-d):

sudo docker-compose up --build -d

6. The application will automatically start and can be accessed at:
http://localhost:8000/

7. Cron Jobs
The cron jobs will start automatically and run at the specified intervals defined in your cron configuration.

8. Stop the Containers
To stop the containers, including all volumes, use the following command:

docker-compose down -v




