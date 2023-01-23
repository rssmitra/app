
  // playAudio(data.no,data.loket,data.type);
  
  function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
  }

  function playAudio(num,loket) { 
  var num1 = num;
    (function() {
      var numstring = num.toString();
      var res = numstring.split("");
      var numlenght = numstring.length;
      var Mp3Queue = function(container, files) {
        var index = 1;
        var nextindex;
        if(!container || !container.tagName || container.tagName !== 'AUDIO') throw 'Invalid container';
        if(!files || !files.length)throw 'Invalid files array';   

        var playNext = function() {
          // panggil nomor urut 1 sampai 9
          if (numlenght == 1) {
            if(index < files.length) {
              container.src = files[index];
              index += 1;
              if (index == 3) {
                if (num == 1){
                  index = 3;
                } else if (num == 2){
                  index = 4;
                } else if (num == 3){
                  index = 5;
                } else if (num == 4){
                  index = 6;
                } else if (num == 5){
                  index = 7;
                } else if (num == 6){
                  index = 8;
                } else if (num == 7){
                  index = 9;
                } else if (num == 8){
                  index = 10;
                } else if (num == 9){
                  index = 11;
                }
              } else if (index > 3) {
                if (num > 0){
                  index = 18; 
                }
                num = 0;
              }
            } else {
              container.removeEventListener('ended', playNext, false);
            }
          // panggil nomor urut 10 sampai 99
          } else if (numlenght == 2) {
            if(index < files.length) {
              container.src = files[index];
              index += 1;
              if (index == 3) {
                if (num == 10){
                  index = 12;
                  num = -1;
                } else if (num == 11){
                  index = 13;
                  num = -1;
                } else if (num >= 12 && num <=19){
                  if (res[1] == 2) {
                    index = 4;
                  } else if (res[1] == 3) {
                    index = 5;
                  } else if (res[1] == 4) {
                    index = 6;
                  } else if (res[1] == 5) {
                    index = 7;
                  } else if (res[1] == 6) {
                    index = 8;
                  } else if (res[1] == 7) {
                    index = 9;
                  } else if (res[1] == 8) {
                    index = 10;
                  } else if (res[1] == 9) {
                    index = 11;
                  }
                } else if (num >= 20 ){
                  if (res[1] == 0) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    } 
                  } else if (res[1] == 1) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 2) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 3) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 4) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 5) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 6) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 7) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 8) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 9) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  }
                }
              } else if (index > 3) {
                if (num >= 12 && num <= 19){
                  index = 14;
                  num = -1;
                } else if (num >= 20){
                  if (res[1] == 0){
                    index = 15;
                    num = -1;
                  } else {
                    index = 15;
                    num = -2;
                  }
                } else if (num == -2){
                  if (res[1] == 1){
                    index = 3;  
                  } else if (res[1] == 2){
                    index = 4;
                  } else if (res[1] == 3){
                    index = 5;
                  } else if (res[1] == 4){
                    index = 6;
                  } else if (res[1] == 5){
                    index = 7;
                  } else if (res[1] == 6){
                    index = 8;
                  } else if (res[1] == 7){
                    index = 9;
                  } else if (res[1] == 8){
                    index = 10;
                  } else if (res[1] == 9){
                    index = 11;
                  } 
                  num = -1;
                } else if (num == -1){
                  index = 18; 
                  num = 0;
                }
              }
            } else {
              container.removeEventListener('ended', playNext, false);
            }
          // panggil nomor urut 100 sampai 999
          } else if (numlenght == 3) {
            if(index < files.length) {
              container.src = files[index];
              index += 1;
              if (index == 3) {
                if (res[0] == 1){
                  index = 17;
                }else if (num >= 200){
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    } 
                }
              }else if (index > 3) {
                if (num == 100){
                  num == -1;
                } else if (num > 100 && num < 200){
                  if (res[1] == 0){
                    if (res[2] == 1){
                      index = 3;
                    } else if (res[2] == 2){
                      index = 4;
                    } else if (res[2] == 3){
                      index = 5;
                    } else if (res[2] == 4){
                      index = 6;
                    } else if (res[2] == 5){
                      index = 7;
                    } else if (res[2] == 6){
                      index = 8;
                    } else if (res[2] == 7){
                      index = 9;
                    } else if (res[2] == 8){
                      index = 10;
                    } else if (res[2] == 9){
                      index = 11;
                    } 
                    num = -1;
                  } else if (res[1] == 1){
                    if (res[2] == 0){
                      index = 12;
                      num = -1;
                    } else if (res[2] == 1){
                      index = 13;
                      num = -1;
                    } else if (res[2] >= 2){
                      if (res[2] == 2){
                        index = 4;
                      } else if (res[2] == 3){
                        index = 5;
                      } else if (res[2] == 4){
                        index = 6;
                      } else if (res[2] == 5){
                        index = 7;
                      } else if (res[2] == 6){
                        index = 8;
                      } else if (res[2] == 7){
                        index = 9;
                      } else if (res[2] == 8){
                        index = 10;
                      } else if (res[2] == 9){
                        index = 11;
                      }
                      num = -2;
                    }
                  } else if (res[1] >= 2){
                    if (res[2] == 0){
                      if (res[1] == 2){
                        index = 4;
                      } else if (res[1] == 3){
                        index = 5;
                      } else if (res[1] == 4){
                        index = 6;
                      } else if (res[1] == 5){
                        index = 7;
                      } else if (res[1] == 6){
                        index = 8;
                      } else if (res[1] == 7){
                        index = 9;
                      } else if (res[1] == 8){
                        index = 10;
                      } else if (res[1] == 9){
                        index = 11;
                      }
                      num = -3;
                    } else if (res[2] >= 1){
                      if (res[1] == 2){
                        index = 4;
                      } else if (res[1] == 3){
                        index = 5;
                      } else if (res[1] == 4){
                        index = 6;
                      } else if (res[1] == 5){
                        index = 7;
                      } else if (res[1] == 6){
                        index = 8;
                      } else if (res[1] == 7){
                        index = 9;
                      } else if (res[1] == 8){
                        index = 10;
                      } else if (res[1] == 9){
                        index = 11;
                      }
                      num = -4;
                    }
                    
                  }
                } else if (num >= 200){
                  if ((res[1] == 0) && (res[2] == 0)) {
                    index = 16;
                    num = -1;
                  }else {
                    index = 16;
                    num = -6;
                  }
                } else if (num == -6){
                  if (res[1] == 0){
                    if (res[2] == 1){
                      index = 3;
                    } else if (res[2] == 2){
                      index = 4;
                    } else if (res[2] == 3){
                      index = 5;
                    } else if (res[2] == 4){
                      index = 6;
                    } else if (res[2] == 5){
                      index = 7;
                    } else if (res[2] == 6){
                      index = 8;
                    } else if (res[2] == 7){
                      index = 9;
                    } else if (res[2] == 8){
                      index = 10;
                    } else if (res[2] == 9){
                      index = 11;
                    }
                    num = -1;
                  } else if (res[1] == 1){
                    if (res[2] == 0){
                      index = 12;
                      num = -1;
                    } else if (res[2] == 1){
                      index = 13;
                      num = -1;
                    } else if (res[2] >= 2){
                      if (res[2] == 2){
                        index = 4;
                      } else if (res[2] == 3){
                        index = 5;
                      } else if (res[2] == 4){
                        index = 6;
                      } else if (res[2] == 5){
                        index = 7;
                      } else if (res[2] == 6){
                        index = 8;
                      } else if (res[2] == 7){
                        index = 9;
                      } else if (res[2] == 8){
                        index = 10;
                      } else if (res[2] == 9){
                        index = 11;
                      }
                      num = -2;
                    } 
                  } else if (res[1] >= 2){
                    if (res[2] == 0){
                      if (res[1] == 2){
                        index = 4;
                      } else if (res[1] == 3){
                        index = 5;
                      } else if (res[1] == 4){
                        index = 6;
                      } else if (res[1] == 5){
                        index = 7;
                      } else if (res[1] == 6){
                        index = 8;
                      } else if (res[1] == 7){
                        index = 9;
                      } else if (res[1] == 8){
                        index = 10;
                      } else if (res[1] == 9){
                        index = 11;
                      }
                      num = -3;
                    } else if (res[2] >= 1){
                      if (res[1] == 2){
                        index = 4;
                      } else if (res[1] == 3){
                        index = 5;
                      } else if (res[1] == 4){
                        index = 6;
                      } else if (res[1] == 5){
                        index = 7;
                      } else if (res[1] == 6){
                        index = 8;
                      } else if (res[1] == 7){
                        index = 9;
                      } else if (res[1] == 8){
                        index = 10;
                      } else if (res[1] == 9){
                        index = 11;
                      }
                      num = -4;
                    }
                    
                  }
                } else if (num == -5){
                  if (res[2] == 1){
                    index = 3;  // untuk puluhan
                  } else if (res[2] == 2){
                    index = 4;
                  } else if (res[2] == 3){
                    index = 5;
                  } else if (res[2] == 4){
                    index = 6;
                  } else if (res[2] == 5){
                    index = 7;
                  } else if (res[2] == 6){
                    index = 8;
                  } else if (res[2] == 7){
                    index = 9;
                  } else if (res[2] == 8){
                    index = 10;
                  } else if (res[2] == 9){
                    index = 11;
                  }
                  num = -1;
                }  else if (num == -7){
                  index = 16; // untuk ratus
                  
                } else if (num == -1){
                  index = 18; // langsung ke loket
                  num = 0;
                } else if (num == -2){
                  index = 14; // untuk belasan
                  num = -1;
                } else if (num == -3){
                  index = 15; // untuk puluhan
                  num = -1;
                } else if (num == -4){
                  index = 15; // untuk puluhan
                  num = -5;
                } 
              } 
            } else {
              container.removeEventListener('ended', playNext, false);
            }
          }
        };
        container.addEventListener('ended', playNext);
        container.src = files[0];
      };

      var container = document.getElementById('container');			

      /*find loket voice*/
      voice_loket = get_voice_loket(loket);
      /*alert(type);*/
      new Mp3Queue(container, [
        'assets/suara/ding.mp3',    // 0
        'assets/suara/nomor-urut.wav',  // 1
        'assets/suara/a.mp3',     // 2
        'assets/suara/satu.wav',    // 3
        'assets/suara/dua.wav',   // 4
        'assets/suara/tiga.wav',    // 5
        'assets/suara/empat.wav',   // 6
        'assets/suara/lima.wav',    // 7
        'assets/suara/enam.wav',    // 8
        'assets/suara/tujuh.wav',   // 9
        'assets/suara/delapan.wav', // 10
        'assets/suara/sembilan.wav',  // 11
        'assets/suara/sepuluh.wav', // 12
        'assets/suara/sebelas.wav', // 13
        'assets/suara/belas.wav',   // 14
        'assets/suara/puluh.wav',   // 15
        'assets/suara/ratus.wav',   // 16
        'assets/suara/seratus.wav', // 17
        'assets/suara/loket.wav',   // 18
        'assets/suara/'+voice_loket+''   // 19
        
      ]);


    })();
  }

  function get_voice_loket(loket){
    strLoket = loket.toString();
    
    switch(strLoket) {
      case '1':
        voice_file = 'satu.wav';
        break;

      case '2':
        voice_file = 'dua.wav';
        break;

      case '3':
        voice_file = 'tiga.wav';
        break;

      case '4':
        voice_file = 'empat.wav';
        break;

      case '5':
        voice_file = 'lima.wav';
        break;

      case '6':
        voice_file = 'enam.wav';
        break;

      case '7':
        voice_file = 'tujuh.wav';
        break;

      case '8':
        voice_file = 'delapan.wav';
        break;

      case '9':
        voice_file = 'sembilan.wav';
        break;

      default:
        voice_file = 'satu.wav';

    }

    return voice_file;

  }