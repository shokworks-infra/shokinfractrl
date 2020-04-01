aws rds describe-db-instances --profile shokworks --region us-east-1 \
	--query DBInstances[*].[DBName,DBInstanceIdentifier,AllocatedStorage,Engine,PubliclyAccessible,MasterUsername,LatestRestorableTime,DBInstanceStatus,Endpoint.[Address]] --output text | awk -v fmt="'%s','%s','%s','%s','%s','%s','%s','%s'" -v fmt1=",'%s'" 'BEGIN {linea=0; datos=""; }
   { 
	   if (linea == 0) {
	      printf( fmt, $1,$2,$3,$4,$5,$6,$7,$8);
	      linea++;
           } else if (linea == 1) {
	     
	      linea=0;
	      printf fmt1, $0;
	   }
   }
'
# awk -v fmt="'%s','%s','%s','%s','%s','%s','%s','%s'" '{printf fmt, $1,$2,$3,$4,$5,$6,$7,$8}''
