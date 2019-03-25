# php-nginx-mysql

**Prerequisite**
1. folder /data/code and /data/code2
2. PHP index.php copied to the above folders

**Apply the Yaml's in the following order**

 1. nginx_configMap.yaml
 2. nginx_deployment.yaml
 3. Build docker file and push to repo or use mine
 4. php_deployment.yaml
 5. mysql_deployment.yaml
 6. ingress controller (details below)
 7. ingress_secret.yaml 
 8. ingress.yaml
 9. Test app1 and app2

# Ingress Controller deployment

    git clone https://github.com/nginxinc/kubernetes-ingress.git
    cd kubernetes-ingress
    cd deployments
    kubectl apply -f common/ns-and-sa.yaml
    kubectl apply -f common/nginx-config.yaml
    kubectl apply -f rbac/rbac.yaml
    kubectl apply -f deployment/nginx-ingress.yaml
    
this creates your ingress controller, next apply ingress.yaml for the ingress   
## Database mysql
  you will need a DB called myDB and myDB2 with data for the code to work, run the following client and create the DB
  

    kubectl run -it --rm --image=mysql:5.6 --restart=Never mysql-client -- mysql -h mysql -ppassword
    CREATE DATABASE myDB;
    USER myDB;
    CREATE TABLE people (name VARCHAR(20), place VARCHAR(20);
    INSERT INTO people VALUES ('SJ', 'PA');
    INSERT INTO people VALUES ('MJ', 'VA');
 
    

## Testing

    kubectl get svc -o wide
Pick the IP Address

    IC_IP=10.102.250.242
    IC_HTTPS_PORT=443
    curl --resolve my.test.com:$IC_HTTPS_PORT:$IC_IP https://my.test.com:$IC_HTTPS_PORT/app1 --insecure


## php-fpm custom image

Under phpbuild-fpm folder you will find the Dockerfile, follow the steps to build and push to your repo

    docker build .
    docker tag <Image ID> sjmax/php-7.1-fpm
    docker push sjmax/php-7.1-fpm
Now you can use the above image in your php_deployment.yaml file

## Known Issues

More testing underway for app2 and php slowness to retrieve data from mysql
