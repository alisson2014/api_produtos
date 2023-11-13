#!/bin/bash

port=8080
root_dir="public"

if ! command -v php &> /dev/null; then
    echo "O PHP não está instalado. Por favor, instale o PHP antes de continuar."
    exit 1
fi

if [ ! -d "$root_dir" ]; then
    echo "A pasta raiz '$root_dir' não existe."
    exit 1
fi

php -S "localhost:$port" -t "$root_dir"