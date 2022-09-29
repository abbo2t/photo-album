variable "environment" {
  type = string
}

variable "resource_group_name" {
  type = string
}

variable "apim_instance_name" {
  type = string
}

variable "product_portfolio" {
  type = string
}

variable "name" {
  type = string
}

variable "display_name" {
  type = string
}

variable "service_url" {
  type = string
}

variable "path" {
  type    = string
}

variable "auth_server_url" {
  type = string
}

variable "sap_client_id" {
  type    = string
}

variable "log_analytics_workspace_name" {
  type = string
}

variable "global_key" {
  type = string
}

variable "cc_to_sap_list" {
  type = list(object({
    app_client_id = string
    sap_app_id    = string
  }))
}


variable "environment_segment" {
 type = string
}
