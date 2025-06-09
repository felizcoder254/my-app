# ─── Build stage (assets + Composer) ─────────────────────
FROM node:18 AS builder
WORKDIR /app

# 1) Copy only package.json (and package-lock.json if you had one)
COPY package.json ./

# 2) Install Node deps via npm
RUN npm install

# 3) Copy the rest of your code
COPY . .

# 4) Build your frontend assets
RUN npm run build
