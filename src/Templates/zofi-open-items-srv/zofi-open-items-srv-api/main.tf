module "auth_sap" {
  source              = "../../../../../../modules/api-policy-auth-sap"
  openid_config_url   = var.auth_server_url
  sap_client_id       = var.sap_client_id
  mtls_certificate_id = "{{mtls-certificate-id}}"
  cc_to_sap_list      = var.cc_to_sap_list
}

module "policy" {
  source     = "../../../../../../modules/api-policy-builder"
  global_key = var.global_key
  auth_type  = "custom"
  auth_custom = {
    policy_fragment = module.auth_sap.fragment
  }
}


module "product_catalog" {
  source            = "../../../../../../modules/product-catalog"
  product_portfolio = var.product_portfolio
}

module "api" {
  depends_on          = [module.policy]
  source              = "../../../../../../modules/api-v2"
  environment_segment = var.environment_segment
  environment         = var.environment
  resource_group_name = var.resource_group_name
  apim_instance_name  = var.apim_instance_name
  name                = var.name
  display_name        = var.display_name
  description         = "{{description}}"
  products            = ["dealer-account-om"]
  policy_content      = module.policy.rendered

  frontend = {
    namespace = module.product_catalog.namespace
    path      = var.path
  }

  backend = {
    service_url = var.service_url
  }

  schema = {
    format  = "openapi+json"
    content = templatefile("${path.module}/schema.json", {info_title = var.display_name})
  }

  versioning = {
    revision = "1"
  }

  logging_sampling_percentage = 100
  logging_log_analytics_workspace_name = var.log_analytics_workspace_name
  logging_additional_headers = ["Authorization"]
}

output "api_config" {
  value = module.api.api_full_path
}
