#! /bin/bash

IFS_BK=$IFS
IFS=$'\r\n'
describe=$(aws cloudformation describe-stacks --stack-name $STACK_NAME --query "Stacks[0].StackStatus")
exit_code=$?
status=$(echo $describe | sed -e 's/[ \"]//g')
IFS=$IFS_BK

if [ $exit_code -eq 0 ]; then
    aws cloudformation delete-stack --stack-name $STACK_NAME
    exit_code=$?
    if [ $exit_code -ne 0 ]; then
        exit $exit_code
    fi
    # TODO DELETE_COMPLETE になるまで待つ
fi
