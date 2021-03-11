#! /bin/bash

aws cloudformation create-stack --stack-name $STACK_NAME --template-url $TEMPLATE_URL
exit_code=$?
if [ $exit_code -ne 0 ]; then
    exit $exit_code
fi
