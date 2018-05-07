aws ec2 describe-reserved-instances --filter="Name=state,Values=active" > data/reserved.json
aws ec2 describe-instances --filter="Name=instance-state-name,Values=running" > data/instances.json