1. Start Docker
Make sure Docker is installed and running on your system before proceeding.


2. Pull Required Docker Images
Before running docker-compose, you may need to pull some Docker images. For example, to pull the ubuntu:20.04 image, run:

docker pull ubuntu:20.04 and docker pull composer:2.5.5

3. Ensure Port 8000 Is Available
Before running the project, ensure that nothing is already using port 8000. If another service is using this port, stop or reconfigure it to avoid conflicts.

4. Start the Project
To start the project, run the following command, which will build the containers and start them in detached mode (-d):

sudo docker-compose up --build -d

5. The application will automatically start and can be accessed at:
http://localhost:8000/

6. Cron Jobs
The cron jobs will start automatically and run at the specified intervals defined in your cron configuration.

7. Stop the Containers
To stop the containers, including all volumes, use the following command:

docker-compose down -v




