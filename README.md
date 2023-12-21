# lbaw2391

# Gamma

Gamma are an academic social network designed to foster collaborative learning. Our platform provides a space for students to explore new subjects and assist each other in their academic pursuits, whose mission is to make learning accessible and enjoyable for everyone. We believe in the power of community and the importance of sharing knowledge.

In Gamma, you can connect with other people by seeing, reacting or commenting on their posts, as well as trying to build a community by creating and joining groups. Besides that,
you are also able to make friend and connections with other people. You can also express your opinions by creating posts, writting comments or voting on polls. 


# 1. Installation

## 1.1. Using docker

```bash
docker run -it -p 8000:80 --name=lbaw2391 -e DB_DATABASE="lbaw2391" -e DB_SCHEMA="lbaw2391" -e DB_USERNAME="lbaw2391" -e DB_PASSWORD="vUVTyupt" git.fe.up.pt:5050/lbaw/lbaw2324/lbaw2391
```

And then you can access the local website on `localhost:8000`

## 1.2. On your machine

After cloning the respository you can run the following commands:

- `cd lbaw2391`
- `docker compose up -d`
- `npm run dev`

And then you can access the local website on `localhost:8000`


# 2. Usage

## 2.1. Link

If you have access to the FEUP VPN you can access the website via the following link:

https://lbaw2391.lbaw.fe.up.pt 

## 2.2. Credentials

#### 2.1. Administration Credentials

> Administration URL: https://lbaw2391.lbaw.fe.up.pt/admin/user

| Username | Password |
| -------- | -------- |
| admin    | admin    |

#### 2.2. User Credentials

| Type          | Username | Password |
| ------------- | -------- | -------- |
| basic account | adalovelace | password3 |
| group owner (prolog enthusiasts group)  | johndoe  | hello    |
