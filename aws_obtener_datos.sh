
regiones=$(cat regiones.txt)
cuentas=$(cat  ~/.aws/credentials | grep '\[' | egrep -v 'default|asambleanacional|conexpar' | awk 'BEGIN {FS="["} {print $2}' |awk 'BEGIN {FS="]"} {print $1}')

regiones="us-east-1 us-east-2 us-west-1 us-west-2 eu-west-2 ap-southeast-1 ap-southeast-2 eu-central-1 us-east-1 us-east-2"
cuentas="kinesis-group"

fecha=$(date +"%Y-%m-%d_%H_%M")
archSal="Instancias_por_cuenta_region_${fecha}.txt"
archTemp=""

rm -f $archSal

for c in $(echo $cuentas) ; do 

   for r in $(echo $regiones) ; do

      archTemp="${c}_${r}_${fecha}.rpt"
      # archTemp="temporal"
      
      # aws ec2 describe-instances --profile $c --region $r  --filters "Name=tag:Environment,Values=production,Test" --output table > ver
      aws ec2 describe-instances --profile $c --region $r   --output table > $archTemp

      lin=$(cat $archTemp | wc -l)

      echo "Procesando Cuenta $c en la Region $r con $lin registros  archivo $archTemp" 

      if (( $lin < 4 )) ; then 
        # Si la cuenta no tiene instancias en la region r no se procesa se elimina el archivo.
        rm -f  $archTemp
        echo 

      else 

         echo "iniciocuenta;$c" >> $archSal
         echo "inicioregion;$r" >> $archSal

         cat $archTemp | sed 's/|//g' | grep -v '+' | grep -v '\-\-' | awk '{

            if(NF != 1) {
      
              if ($1 != "Key" && $2 != "Value") {
                 print $1";"$2;

              } 
            } else {
              print "titulo;"$1;
            }

        }' >> $archSal

      fi

  done      

done   

sed -i '/titulo;DescribeInstances/d'

# Fin
