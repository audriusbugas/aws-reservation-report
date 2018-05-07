aws ec2 describe-reserved-instances --filter="Name=state,Values=active" > app/logs/reserved.json
aws ec2 describe-instances --filter="Name=instance-state-name,Values=running" > app/logs/instances.json