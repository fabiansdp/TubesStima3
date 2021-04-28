<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ChatBot</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
        
        <!-- Styles -->
        <link rel="stylesheet" href="css/app.css">
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body>
        <div class="container clearfix">
            <div class="chat">
                <div class="chat-header clearfix">
                    <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar" />
                    
                    <div class="chat-about">
                        <div class="chat-with">ChatBot Tugas</div>
                        <div class="chat-num-messages">Mencatat Tugas</div>
                    </div>
                    <i class="fa fa-star"></i>
                </div> <!-- end chat-header -->
                
                <div class="chat-history">
                    <ul>
                        <li class="clearfix">
                            <div class="message-data align-right">
                                <span class="message-data-time" >10:10 AM, Today</span> &nbsp; &nbsp;
                                <span class="message-data-name" >User</span> <i class="fa fa-circle me"></i>
                            
                            </div>
                            <div class="message other-message float-right">
                                Hi Vincent, how are you? How is the project coming along?
                            </div>
                        </li>
                        
                        <li>
                            <div class="message-data">
                                <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                                <span class="message-data-time">10:12 AM, Today</span>
                            </div>
                            <div class="message my-message">
                                Are we meeting today? Project has been already finished and I have results to show you.
                            </div>
                        </li>
                    </ul>
                    
                </div> <!-- end chat-history -->
            
                <div class="chat-message clearfix">
                    <iframe name="votar" style="display:none;"></iframe>
                    <form action="/" method="POST" target="votar">
                        @csrf
                        <textarea name="value" id="message-to-send" placeholder ="Type your message" rows="3"></textarea>
                                
                        <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-file-image-o"></i>
                        
                        <button type="submit">Send</button>
                    <form>
                </div> <!-- end chat-message -->
            
            </div> <!-- end chat -->
            
        </div> <!-- end container -->
        <script id="message-template" type="text/x-handlebars-template">
            <li class="clearfix">
                <div class="message-data align-right">
                    <span class="message-data-time" >Today</span> &nbsp; &nbsp;
                    <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>
                </div>
                <div class="message other-message float-right">
                    Halo
                </div>
            </li>
        </script>

        <script id="message-response-template" type="text/x-handlebars-template">
            <li>
                <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">Today</span>
                </div>
                <div class="message my-message">
                    chat
                </div>
            </li>
        </script>

        <script >
            (function(){
                var chat = {
                    messageToSend: '',
                    messageResponses: [
                        'Why did the web developer leave the restaurant? Because of the table layout.',
                        'How do you comfort a JavaScript bug? You console it.',
                        'An SQL query enters a bar, approaches two tables and asks: "May I join you?"',
                        'What is the most used language in programming? Profanity.',
                        'What is the object-oriented way to become wealthy? Inheritance.',
                        'An SEO expert walks into a bar, bars, pub, tavern, public house, Irish pub, drinks, beer, alcohol'
                    ],
                    init: function() {
                        this.cacheDOM();
                        this.bindEvents();
                        this.render();
                    },
                    cacheDOM: function() {
                        this.$chatHistory = $('.chat-history');
                        this.$button = $('button');
                        this.$textarea = $('#message-to-send');
                        this.$chatHistoryList =  this.$chatHistory.find('ul');
                    },
                    bindEvents: function() {
                        this.$button.on('click', this.addMessage.bind(this));
                        this.$textarea.on('keyup', this.addMessageEnter.bind(this));
                    },
                    render: function() {
                        this.scrollToBottom();
                        if (this.messageToSend.trim() !== '') {
                            var template = Handlebars.compile( $("#message-template").html());
                            var context = { 
                                messageOutput: this.messageToSend,
                                time: this.getCurrentTime()
                            };

                            this.$chatHistoryList.append(template(context));
                            this.scrollToBottom();
                            // this.$textarea.val('');
                            
                            // responses
                            var templateResponse = Handlebars.compile( $("#message-response-template").html());
                            var contextResponse = { 
                                response: this.getRandomItem(this.messageResponses),
                                time: this.getCurrentTime()
                            };
                            
                            setTimeout(function() {
                                this.$chatHistoryList.append(templateResponse(contextResponse));
                                this.scrollToBottom();
                            }.bind(this), 1500);
                        }
                    
                    },
                    
                    addMessage: function() {
                        this.messageToSend = this.$textarea.val()
                        this.render();        
                    },

                    addMessageEnter: function(event) {
                        // enter was pressed
                        if (event.keyCode === 13) {
                            this.addMessage();
                        }
                    },
                    scrollToBottom: function() {
                        this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
                    },
                    getCurrentTime: function() {
                        return new Date().toLocaleTimeString().
                            replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                    },
                    getRandomItem: function(arr) {
                        return arr[Math.floor(Math.random()*arr.length)];
                    }
                    
                };
                
                chat.init();
            })();
        
        </script>
    </body>
</html>
