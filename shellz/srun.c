#define _GNU_SOURCE
#include <libgen.h>
#include <signal.h>
#include <stdio.h>
#include <stdlib.h>
#include <sys/resource.h>
#include <sys/time.h>
#include <unistd.h>
pid_t pid;

void time_handler ()
{
  fputs("error in time", stderr);
  signal(SIGXCPU, SIG_DFL);
  raise(SIGXCPU);
}

 //argv[1] : executable file
 //argv[2] : maximum time
 //arg[3] : stats log
int
  main(argc, argv, envp)
  int argc;
  char* argv[];
  char* envp[];
{
  if (argc < 3)
  {
    printf ("usage: %s <executable file> <maximum time> <statix log>", argv[0]);
    exit(2);
  }
  
  signal (SIGXCPU, time_handler);
  struct rlimit safe_time;
  safe_time.rlim_cur=1;
  safe_time.rlim_max=2;
  if (setrlimit (RLIMIT_CPU, &safe_time)<0)
    perror("setrlimit");
  
  pid=fork();
  if (pid>0)
  {
    char sandbox_read[100], sandbox_write[100];
    sscanf(sandbox_read, "SANDBOX_READ=/usr/share/sanbox:%s", dirname(argv[1]));
    sscanf(sandbox_write, "SANDBOX_WRITE=%s", dirname(argv[1]));
    execl("/usr/bin/sandbox", "sanbox", argv[1], (char*)0, sandbox_read
      , sandbox_write, (char*)0);
  }
  else if (pid==0)
  {
    sleep(atoi(argv[2]));
    kill(getppid(), 9);
  }
  else
    perror("fork");
}