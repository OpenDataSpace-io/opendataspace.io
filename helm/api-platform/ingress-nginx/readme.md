### Ingress-nginx

Link to the project: https://github.com/kubernetes/ingress-nginx
Helm-chart: https://github.com/kubernetes/ingress-nginx/tree/main/charts/ingress-nginx

## GKE:
Installation:
1. Your user needs to have cluster-admin permissions on the cluster. This can be done with the following command:
```
kubectl create clusterrolebinding cluster-admin-binding \
  --clusterrole cluster-admin \
  --user $(gcloud config get-value account)
```
2. Get helm repo info:
```
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx
helm repo update
```
3. Install ingress-controller:
```
 helm -n ingress-nginx upgrade -i <release-name> ingress-nginx/ingress-nginx \
 --set=controller.replicaCount=2 \
 --create-namespace
```