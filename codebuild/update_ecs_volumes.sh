#! /bin/bash

#yes | yum install amazon-efs-utils
#
#confpath=/etc/amazon/efs/efs-utils.conf
#sed -i -e "s/#\(region = \)us-east-1/\1${AWS_DEFAULT_REGION}/g" $confpath
#sed -i -e "s/\(dns_name_format = \){az}.{fs_id}.efs.{region}.{dns_name_suffix}/\1{fs_id}.efs.{region}.{dns_name_suffix}/g" $confpath
#
#echo ==== efs-utils.conf ====
#cat $confpath
#echo ========================
#
#cd /mnt
#mkdir efs
#mount -t efs ${EFS_ID}:/ efs
#rm -rf /mnt/efs/fs1/app
#
#cd $CODEBUILD_SRC_DIR
#
# cp -r app /mnt/efs/fs1/app
# /mnt/efs/fs1/mysql-data

#yes | yum install nfs-common
#
#echo mounting EFS ...
#echo EFS ID: ${EFS_ID}
#echo Region: ${AWS_DEFAULT_REGION}
#
#mkdir /efs
#mount -t nfs4 -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport ${EFS_ID}.efs.${AWS_DEFAULT_REGION}.amazonaws.com:/ /efs

echo ==== /mnt/efs list ====
ls -la ${MOUNT_DIR}
echo =======================

cd $CODEBUILD_SRC_DIR
pwd

echo remove app files.
rm -rf ${MOUNT_DIR}/fs1/app

echo copy app files.
cp -r app ${MOUNT_DIR}/fs1/app
