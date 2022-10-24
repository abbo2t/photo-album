#
## {{display-name}} - Environment Modules
#
module "{{module-name}}_dev" {
  count                        = (var.environment == "sandbox") ? 1 : 0
  source                       = "./{{source}}-api"
  environment                  = var.environment
  environment_segment          = var.environment_segment
  resource_group_name          = var.resource_group_name
  apim_instance_name           = var.apim_instance_name
  product_portfolio            = var.product_portfolio
  log_analytics_workspace_name = var.log_analytics_workspace_name
  global_key                   = "{{global-key}}_dev"
  name                         = "{{name}}-DEV"
  display_name                 = "{{display-name}} - DEV"
  path                         = "{{path}}/dev"
  service_url                  = "https://{{service_subdomain_dev}}.sap.johndeerecloud.com/sap/opu/odata/sap/{{service-url}}"
  auth_server_url              = "https://sso-dev.johndeere.com/oauth2/ausi7tpzliZSTWOp10h7/.well-known/oauth-authorization-server"
  cc_to_sap_list = []
  sap_client_id = {{sap_client_id_dev}}
}

module "{{module-name}}_qual" {
  count                        = (var.environment == "devl") ? 1 : 0
  source                       = "./{{source}}-api"
  environment                  = var.environment
  environment_segment          = var.environment_segment
  resource_group_name          = var.resource_group_name
  apim_instance_name           = var.apim_instance_name
  product_portfolio            = var.product_portfolio
  log_analytics_workspace_name = var.log_analytics_workspace_name
  global_key                   = "{{global-key}}_qual"
  name                         = "{{name}}-QUAL"
  display_name                 = "{{display-name}} - QUAL"
  path                         = "{{path}}/qual"
  service_url                  = "https://{{service_subdomain_qual}}.sap.johndeerecloud.com/sap/opu/odata/sap/{{service-url}}"
  auth_server_url              = "https://sso-qual.johndeere.com/oauth2/ausi350kyb3DS4mMI0h7/.well-known/oauth-authorization-server"
  cc_to_sap_list = []
  sap_client_id = {{sap_client_id_qual}}
}

module "{{module-name}}_cert" {
  count                        = (var.environment == "devl") ? 1 : 0
  source                       = "./{{source}}-api"
  environment                  = var.environment
  environment_segment          = var.environment_segment
  resource_group_name          = var.resource_group_name
  apim_instance_name           = var.apim_instance_name
  product_portfolio            = var.product_portfolio
  log_analytics_workspace_name = var.log_analytics_workspace_name
  global_key                   = "{{global-key}}_cert"
  name                         = "{{name}}-CERT"
  display_name                 = "{{display-name}} - CERT"
  path                         = "{{path}}/cert"
  service_url                  = "https://{{service_subdomain_cert}}.sap.johndeerecloud.com/sap/opu/odata/sap/{{service-url}}"
  auth_server_url              = "https://sso-cert.johndeere.com/oauth2/aus972savuI4PAAgc1t7/.well-known/oauth-authorization-server"
  cc_to_sap_list = []
  sap_client_id = {{sap_client_id_cert}}
}

module "{{module-name}}_prod" {
  count                        = (var.environment == "prod") ? 1 : 0
  source                       = "./{{source}}-api"
  environment                  = var.environment
  environment_segment          = var.environment_segment
  resource_group_name          = var.resource_group_name
  apim_instance_name           = var.apim_instance_name
  product_portfolio            = var.product_portfolio
  log_analytics_workspace_name = var.log_analytics_workspace_name
  global_key                   = "{{global-key}}_prod"
  name                         = "{{name}}-PROD"
  display_name                 = "{{display-name}} - PROD"
  path                         = "{{path}}"
  service_url                  = "https://{{service_subdomain_prod}}.sap.johndeerecloud.com/sap/opu/odata/sap/{{service-url}}"
  auth_server_url              = "https://sso.johndeere.com/oauth2/aus9fatalq0bxmt361t7/.well-known/oauth-authorization-server"
  cc_to_sap_list = []
  sap_client_id = {{sap_client_id_prod}}
}
