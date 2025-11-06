# front/Dockerfile
FROM node:18-alpine

WORKDIR /app

COPY package*.json ./
RUN if [ -f package.json ]; then npm ci --silent || true; fi

COPY . .

CMD ["npm", "start"]
