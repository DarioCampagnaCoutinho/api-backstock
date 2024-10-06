param(
    [string]$Task,
    [string]$cmd
)

function Help {
    Write-Host "Comandos disponíveis:"
    Write-Host "  ./Makefile.ps1 -Task Up          - Subir o ambiente Docker"
    Write-Host "  ./Makefile.ps1 -Task Down        - Derrubar o ambiente Docker"
    Write-Host "  ./Makefile.ps1 -Task Build       - Fazer build do ambiente Docker"
    Write-Host "  ./Makefile.ps1 -Task Restart     - Reiniciar os containers"
    Write-Host "  ./Makefile.ps1 -Task Bash        - Acessar o container PHP"
    Write-Host "  ./Makefile.ps1 -Task Migrate     - Executar as migrações do banco de dados"
    Write-Host "  ./Makefile.ps1 -Task Seed        - Rodar os seeders"
    Write-Host "  ./Makefile.ps1 -Task Artisan -cmd 'command_here' - Rodar comandos artisan"
    Write-Host "  ./Makefile.ps1 -Task Composer    - Rodar o composer install"
    Write-Host "  ./Makefile.ps1 -Task Logs        - Exibir logs dos containers"
}

function Up {
    docker-compose up -d
}

function Down {
    docker-compose down
}

function Build {
    docker-compose up --build -d
}

function Restart {
    docker-compose down
    docker-compose up -d
}

function Bash {
    docker-compose exec php-fpm bash
}

function Migrate {
    docker-compose exec php-fpm php artisan migrate
}

function Seed {
    docker-compose exec php-fpm php artisan db:seed
}

function Artisan {
    docker-compose exec php-fpm php artisan $cmd
}

function Composer {
    docker-compose exec php-fpm composer install
}

function Logs {
    docker-compose logs -f
}

switch ($Task) {
    "Up"       { Up }
    "Down"     { Down }
    "Build"    { Build }
    "Restart"  { Restart }
    "Bash"     { Bash }
    "Migrate"  { Migrate }
    "Seed"     { Seed }
    "Artisan"  { Artisan }
    "Composer" { Composer }
    "Logs"     { Logs }
    Default    { Help }
}
