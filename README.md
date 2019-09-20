# Laravel - Importando Produtos do Excel

Sistema para o cadastro de produtos através de uma planilha exell em queue

## Características

 - Laravel 5.7
 - Vue Js + Vue Bootstrap + Sweetalert + Axios
 - Biblioteca utilizada: [maatwebsite/excel](https://laravel-excel.maatwebsite.nl/)
 
 ## Instalação
  
 - `git clone https://gitlab.com/rmeira/laravel-app-products.git` 
 - Copiar `.env.example` para `.env`
 - Rodar o comando `php artisan key:generate`
 - Configurar o banco de dados de sua preferencia no arquivo `.env`
 - Rodar o comando `php artisan migrate`
 - Planilha de exemplo está em `./public/planilha_exemplo.xlsx`
 
 ## Como usar
 
 #### Developer
 
 ```
 npm run watch
 ```
 
 #### Production
 
 ```
 npm run production
 ```
 
 ## Changelog
 
 Verificar as mudanças realizadas no arquivo [CHANGELOG](CHANGELOG.md).
 
# Laravel-Excel-Import-Products
