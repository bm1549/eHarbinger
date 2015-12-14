#include <stdio.h>
#include <postgresql/libpq-fe.h>
#include <string>

int main() {
 PGconn* conn;
 PGresult* res;
 int rec_count;
 int row;
 int col;
 
 
         conn = PQconnectdb("dbname=bt773 host=localhost user=bt773 password=bt773");

         if (PQstatus(conn) == CONNECTION_BAD) {
                 puts("We were unable to connect to the database");
                 exit(0);
         }
 
		 res = PQexec(conn,
			 "select * from users;");
 

         if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                 puts("We did not get any data!");
                 exit(0);
         }
 
         rec_count = PQntuples(res);
 
         printf("We received %d records.\n", rec_count);
         puts("==========================");
 
         for (row=0; row<rec_count; row++) {
                 for (col=0; col<3; col++) {
                         printf("%s\t", PQgetvalue(res, row, col));
                 }
                 puts("");
         }
 
         puts("==========================");
 
         PQclear(res);
 
         PQfinish(conn);
 
         return 0;
}