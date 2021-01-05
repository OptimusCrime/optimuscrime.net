.PHONY: php-build php-run nginx-build nginx-run stop logs

php-build:
	@docker build -f docker_php.Dockerfile .

php-run:
	@docker run -d -v "$$(pwd)":/site -p 8080:8080 $$(docker images -q | head -n 1)

nginx-build:
	@docker build -f docker_nginx.Dockerfile .

nginx-run:
	@docker run -d -p 8081:80 $$(docker images -q | head -n 1)

stop:
	@docker stop $$(docker ps -a -q)

logs:
	@docker logs $$(docker ps -a -q | head -n 1) -f
