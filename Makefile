# Variáveis
DC=docker compose --file docker-compose.yml --env-file .env

.PHONY: up down migrate logs cache clear help

up:
	$(DC) up -d --build

setup:
	$(DC) up -d --build
	@echo "Aguardando o MySQL ficar ativo..."
	@while ! $(DC) exec svc-stk-mysql mysql -h 127.0.0.1 -uadmin -p123456 -e "SELECT 1" > /dev/null 2>&1; do sleep 2; done
	@echo "MySQL está ativo!"
	$(DC) exec svc-stk-php composer install
	$(DC) exec svc-stk-php php artisan migrate:fresh
	$(DC) exec svc-stk-php php artisan db:seed

down:
	$(DC) down

reset:
	$(DC) down --volumes
	$(DC) up -d --build

logs:
	$(DC) logs -f -n 10

cache:
	$(DC) exec svc-stk-php php artisan cache:clear
	$(DC) exec svc-stk-php php artisan config:clear

clear:
	$(DC) down
	docker system prune --all -f
	@if [ -n "$$(docker volume ls -q)" ]; then docker volume rm $$(docker volume ls -q); fi
	sudo rm -R .docker/

help: # mostra essa ajuda
	@echo "----------------------- HELP ----------------------------"
	@echo "up:     Sobe todos os serviços"
	@echo "setup:  Sobe todos os serviços utilizando o composer e php artisan migrate e php artisan db:seed"
	@echo "down:   Derruba todos os serviços"
	@echo "reset:  Derruba todos os serviços e volumes, depois os sobe novamente"
	@echo "logs:   Observa os logs de todo o ambiente"
	@echo "cache:  Limpa o cache e a configuração da aplicação"
	@echo "clear:  Derruba os serviços, remove volumes e faz prune do sistema"
	@echo "--------------------------------------------------------"
