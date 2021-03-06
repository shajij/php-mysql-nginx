# php-nginx-mysql

**Architecture**
nginx ingress sends traffic to nginx container and the /app1 or /app2 pulls up an index.php file running in php container which inturn connects to the mysql DB and pulls data

**Prerequisite**
1. folder /data/code and /data/code2
2. PHP index.php copied to the above folders from the repo

**Apply the Yaml's in the following order**

 1. nginx_configMap.yaml
 2. nginx_deployment.yaml
 3. Build docker file under phpbuild-fpm folder and push to repo or use mine 
 4. php_deployment.yaml
 5. mysql_deployment.yaml
 6. ingress controller (details below)
 7. ingress_secret.yaml 
 8. ingress.yaml
 9. nginx2_configMap.yaml
 10. nginx2_deployment.yaml
 11. php2_deployment.yaml
 12. Test app1 and app2

# Ingress Controller deployment

    git clone https://github.com/nginxinc/kubernetes-ingress.git
    cd kubernetes-ingress
    cd deployments
    kubectl apply -f common/ns-and-sa.yaml
    kubectl apply -f common/nginx-config.yaml
    kubectl apply -f rbac/rbac.yaml
    kubectl apply -f common/default-server-secret.yaml
    kubectl apply -f deployment/nginx-ingress.yaml
    kubectl create -f service/nodeport.yaml
    
this creates your ingress controller, next apply ingress.yaml for the ingress   
## Database mysql
  you will need a DB called myDB and myDB2 with data for the code to work, run the following mysql client and create the DB
  

    kubectl run -it --rm --image=mysql:5.6 --restart=Never mysql-client -- mysql -h mysql -ppassword
    CREATE DATABASE myDB;
    USE myDB;
    CREATE TABLE people (name VARCHAR(20), place VARCHAR(20));
    INSERT INTO people VALUES ('SJ', 'PA');
    INSERT INTO people VALUES ('MJ', 'VA');
    
    CREATE DATABASE myDB2;
    USE myDB2;`
    CREATE TABLE places (name VARCHAR(20), zip VARCHAR(20));
    INSERT INTO places VALUES ('PA', '19703');
    INSERT INTO places VALUES ('VA', '17831');


## Testing

    kubectl get svc -o wide --all-namespaces
Pick the IP Address of the nginx-ingress

    IC_IP=10.102.250.242
    IC_HTTPS_PORT=443
    curl --resolve my.test.com:$IC_HTTPS_PORT:$IC_IP https://my.test.com:$IC_HTTPS_PORT/app1 --insecure
    curl --resolve my.test.com:$IC_HTTPS_PORT:$IC_IP https://my.test.com:$IC_HTTPS_PORT/app2 --insecure


## php-fpm custom image

Under phpbuild-fpm folder you will find the Dockerfile, follow the steps to build and push to your repo

    docker build .
    docker tag <Image ID> sjmax/php-7.1-fpm
    docker push sjmax/php-7.1-fpm
    
Now you can use the above image in your php_deployment.yaml and php2_deployment.yaml files

## Known Issues

More testing underway

## References

[https://github.com/nginxinc/kubernetes-ingress](https://github.com/nginxinc/kubernetes-ingress)
[https://hackernoon.com/setting-up-nginx-ingress-on-kubernetes-2b733d8d2f45](https://hackernoon.com/setting-up-nginx-ingress-on-kubernetes-2b733d8d2f45)
[https://kubernetes.io/docs/concepts/services-networking/ingress/](https://kubernetes.io/docs/concepts/services-networking/ingress/)
[https://www.w3schools.com/php/php_mysql_connect.asp](https://www.w3schools.com/php/php_mysql_connect.asp)
[https://secure.php.net/manual/en/mysqli.installation.php](https://secure.php.net/manual/en/mysqli.installation.php)
[https://github.com/docker-library/repo-info/tree/master/repos/php/local](https://github.com/docker-library/repo-info/tree/master/repos/php/local)
