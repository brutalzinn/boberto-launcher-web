# boberto-minecraft-launcher-web


# Erro crítico de segurança detectado nessa api. Use o Docker ou qualquer outro orquestrador caso necessite fazer deploy em produção.
# PORTUGUÊS

Este repositório contém uma web  api em PHP para o lançador de minecraft boberto
criador de modpack](https://github.com/brutalzinn/CriadorDeMods)

você precisa renomear env.example para .env e configurar com sua senha.

Você precisa usar o Redis para armazenar em cache todos
modpacks json gerados por php. Eu uso o redis para armazenar em cache todos os diretórios json gerados pelos modpacks para me dar uma melhor latência para o launcher.

# ENGLISH 

This repository contains a web for the boberto minecraft launcher
and a python api with flask to be uses with my [Modpack creator](https://github.com/brutalzinn/CriadorDeMods)

you need to rename env.example to .env and configure with your password.

You need to uses Redis to cache all 
modpacks json generated by php. I uses redis to cache all modpacks generated json directories to gives me a best latency to modpack downloader.

# FRENCH

Ce référentiel contient un site Web pour le lanceur Boberto Minecraft
et une api python avec flacon à utiliser avec mon [Créateur de Modpack](https://github.com/brutalzinn/CriadorDeMods)

vous devez renommer env.example en .env et configurer avec votre mot de passe.


Vous devez utiliser Redis pour tout mettre en cache
modpacks json générés par php. J'utilise redis pour mettre en cache tous les répertoires json générés par les modpacks afin de me donner une meilleure latence pour le téléchargeur de modpack.