variable client_id {
    default = "171d5191-7b77-49db-a8ff-96d4fa1ff675"
}
variable client_secret {
    default = "ccf60696-5e19-488e-88ca-27d746c4c1a3"
}
variable ssh_public_key {
    default = "id_rsa.pub"
}

variable environment {
    default = "dev"
}

variable location {
    default = "westeurope"
}

variable node_count {
  default = 2
}



variable dns_prefix {
  default = "k8stest"
}

variable cluster_name {
  default = "k8stest"
}

variable resource_group {
  default = "kubernetes"
}