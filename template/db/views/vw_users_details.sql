

create view vw_users_details as 
select u.user_id,u.email,u.first_name,u.last_name,u.org_id, o.name as organization_name
from users u 
inner join organizations o on (u.org_id = o.org_id);