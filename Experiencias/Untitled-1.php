permissions:
- id 
- permission -> (administrador, cliente, cliente_vip)
- slug-> slug, tradução

users:
- id 
- email 
- nome 
- password 

role_permissions:
- role_id
- permission_id

procuct_types:
- id 
- type (carne, peixe, vegetais, fruta)
- slug -> slug, tradução

products:
- id 
- name 
- price
- tipo_id => tipos.id
- image_path
- stock

cart:
- id 
- user_id 
- product_id
- quantity


favorites:
- product_id
- user_id