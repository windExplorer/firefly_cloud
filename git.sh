#!/bin/bash

git status
git config core.autocrlf false
git add .
git commit -m '更新'
git push origin master
