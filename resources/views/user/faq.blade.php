@include('user.user-dashboard-base')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

<div class="content-wrapper py-5">
    <div class="mx-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h2 class="display-4 fw-bold">Frequently Asked Questions</h2>
                    <p class="text-muted lead">Find answers to commonly asked questions about Bifonex</p>
                </div>

                <div class="faq-accordion">
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq1', this)">
                            <span class="question-text">What are {{(env('APP_NAME')) }}?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq1" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Bitfonex is a unique innovative crypto tool for mass use an innovation technology that building massive infrastructure
                            project, which combining the innovation of technology the blockchain, web3 and altricial intelligent with connecting
                            three great communities that can benefit each other and bridges the gap between traditional business and the crypto
                            world. Bitfonex offers innovative business solutions which enable merchants from around the world to sell their
                            innovative products/service for crypto market, interacting with a global audience of crypto enthusiasts where we help
                            millions of people transform their lives.
                            </div>
                        </div>
                    </div>

                    <!-- Repeat structure for other FAQ items -->

                    <!-- faq2 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq2', this)">
                            <span class="question-text">How FOMO PROGRAM WORKS?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq2" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Fomo Program is a fun opportunity for people to enjoy advertising their business through games play, click views,
                            video watch, survey ,simple task. Currently the platform has some feature to begin with click views, watch video, task,
                            survey As more and more feature will be added to the platform fomo Games aims to be the number one
                            entertainment platform within the coin ecosystem. When you share your referral link with any of your friends, family or
                            advertise the link and a player signs up at our site that player becomes your referral and they will earn you
                            commission & extra rewards by playing at bifonex.
                            </div>
                        </div>
                    </div>


                     <!-- faq3 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq3', this)">
                            <span class="question-text">{{(env('APP_NAME')) }} Free to use ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq3" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Absolutely. You can use our services for free. Some high reward tasks or license may require purchases but these
                            are entirely optional.
                            </div>
                        </div>
                    </div>


                     <!-- faq4 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq4', this)">
                            <span class="question-text">Do i have to recruit new members?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq4" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            No you do not necessarily have to participate in the referral optional. you decide for yourself!
                            </div>
                        </div>
                    </div>



                    <!-- faq5 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq5', this)">
                            <span class="question-text">I have big audience, how I can get special deals ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq5" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            If you have a website with good traffic or social media account with big audience you can connect with us at
                            support@bifonex.com for special deals. Or apply leader link
                            </div>
                        </div>
                    </div>


                    <!-- faq6 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq6', this)">
                            <span class="question-text">How to reach people ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq6" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            There are many ways to reach people and tell them about biconvex earning cash for this opportunities. Here are
                            some

                            <ul>
                                <li>Tell friend, classmate, neighbor, acquaintance and business partners about {{(env('APP_NAME')) }} .</li>
                                <li>Create your own social medial channel and share you experience these are some of many way to reach
                                new people and Start marketing campaign such as banner advertising, Google ads, social medial ads and
                                son on </li>

                                <li>Start seminars or webinars, local short event </li>
                            </ul>


                            </div>
                        </div>
                    </div>



                    <!-- faq7 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq7', this)">
                            <span class="question-text">Can I see the data of my referral ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq7" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Yes, {{(env('APP_NAME')) }} believes in total transparency and offers all data to the users like username, total refer team and
                            commission earned, date of registration, license package and referral link applied. You can see all of this information
                            in your Affiliate Dashboard
                            </div>
                        </div>
                    </div>


                    <!-- faq8 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq8', this)">
                            <span class="question-text"> Does  {{(env('APP_NAME')) }}  have a referral program ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq8" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Yes you can earn from 10%to 25%bonuses with Coins and cash from your referral partners Use the referral
                            program to receive bonuses and rewards, increase income at an accelerated get high profits
                            With our referral program, you can develop your Professional and personal
                            qualities in all areas. You are geographically independent and can work with us from anywhere in the world.
                            The main goal of company is to provide financial freedom to our partners; these are our innovations
                            that will allow you to become a professional business and achieve the desired success. Company
                            has no limits. We appreciate the trust that you render company to help you achieve your financial goals
                            </div>
                        </div>
                    </div>


                    <!-- faq9 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq9', this)">
                            <span class="question-text">How to deposit ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq9" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                          <ol>
                            <li> click the deposit page, copy the wallet address for payment or user perfect money etc..</li>
                            <li> Use Buy Crypto to deposit any supported currencies provided by bifonex.</li>
                          </ol>
                            </div>
                        </div>
                    </div>


                    <!-- faq10 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq10', this)">
                            <span class="question-text">How to withdraw ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq10" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            click the withdraw page, enter the address of the wallet you need to withdraw and the amount of cryptocurrency
                            </div>
                        </div>
                    </div>


                    <!-- faq11 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq11', this)">
                            <span class="question-text">EXPERT SUPPORT</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq11" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Yes We have live chat click link <br>
                            Customer support is available 24/7 to help you with any questions or issues.
                            </div>
                        </div>
                    </div>


                    <!-- faq12 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq12', this)">
                            <span class="question-text">Is {{(env('APP_NAME')) }} supported in  my country ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq12" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            {{(env('APP_NAME')) }} is available worldwide and open to all users. We have users from every country around the world! Some
                            countries and offers may be restricted to specific locations and some uvp license.
                            </div>
                        </div>
                    </div>



                    <!-- faq13 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq13', this)">
                            <span class="question-text">Can I Create Multiple Accounts to Receive More rewards ?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq13" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            No, creating multiple accounts with different emails is against our policy. Each user is allowed only one account, and
                            KYC must be completed with accurate details to ensure the legitimacy of the rewards.
                            </div>
                        </div>
                    </div>


                    <!-- faq14 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq14', this)">
                            <span class="question-text">Are you leader and how would like to participate in the special leader program. what should I do</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq14" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            Yes you can apply online leader form click this link
                            </div>
                        </div>
                    </div>


                    <!-- faq15 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq15', this)">
                            <span class="question-text">Crypto loans</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq15" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            The crypto loans services is great option for user to take a loan and use coin as collateral. you can borrow money fiat
                            or other asset In crypto loans platform. For the security of these loans, they are low receive interest the borrower's
                            crypto assets. The crypto loan a is available upon submission of collateral. Depending upon the asset quality of coin,
                            borrowers can get loan fiat or other crypto value of their collateral

                            </div>
                        </div>
                    </div>


                    <!-- faq16 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq16', this)">
                            <span class="question-text">Merchants system </span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq16" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                          <ul>
                            <li>The platform consists of different products and services for the merchants that make accepting coin 100%
                            where system can out convert coin into different current for merchant as wish option</li>
                            <li>This platform provides of different services for Travel a possibility to pay for different services with the coin.
                            Whether you need to book a flight, travel or reserve a hotel room for yourself, restaurant real state, some
                            beautiful place Travel is the perfect option for you.</li>
                          </ul>
                            </div>
                        </div>
                    </div>


                    <!-- faq17 -->
                    <div class="faq-item mb-3">
                        <div class="faq-question" onclick="toggleFaq('faq17', this)">
                            <span class="question-text">How to Become pioneer or Royal investor?</span>
                            <i class="fas fa-plus icon-toggle"></i>
                        </div>
                        <div id="faq17" class="faq-answer collapse">
                            <!-- <hr class="divider"> -->
                            <div class="answer-content">
                            During the pre-launch period we target pioneers and leaders, you will get the unique opportunity to build a global
                            business in a way that never been possible before!! As pioneer called a Royal investor, you’ll be able to take part of
                            the company’s success and a greater part of all future income. you get a first-class with license Private unique
                            venture pool that will help you become an a Royal investor if you buy (UVP license ) you get more tokens and earn
                            daily income and your share will be converted to liquid cash for withdrawal Also, through our affiliate program our
                            members can earn with COMPANY A Royal investor you don't need to refer anybody for earn In during this phase
                            prelaunch and we are targeting specification number of people needed on this first phrase If you don’t do anything
                            and no recruit anybody you triple your money and get more reward . This UVP license package available only pro
                            launch but you can buy one or more the license packages come in different levels of feature offering and also offers
                            very beneficial combinations feature and various educational course. These combinations give you access to
                            automatic other feature of platform, modules of education on, blockchain, web3 and altricial intelligent, e-commerce,
                            trading and cryptocurrency. For each level of UVP license packages, you receive a fixed number of promotional
                            tokens and reword. Depending on what license package you purchase, you also qualify for one or more option.
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<style>
.faq-accordion {
    --text-color: #2d3748;
    --hover-color: darkblue;
}

.faq-item {
    border-radius: 0px;
    overflow: hidden;
    border-bottom: 1px solid darkblue;
}

.faq-question {
    padding: 1.25rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;

}

.faq-question:hover {
    color: var(--hover-color);
}

.question-text {
    font-size: 1.3rem;
    font-weight: bold;
}

.icon-toggle {
    font-size: 1rem;
    transition: transform 0.3s ease;
}

.faq-question.active .icon-toggle {
    transform: rotate(45deg);
}

.divider {
    margin: 0;
}

.answer-content {
    padding: 10px;
    color: #4a5568;
    line-height: ;
}

.display-4 {
    font-size: 2.5rem;
    color: #1a202c;
    margin-bottom: 1rem;
}

.lead {
    color: #4a5568;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .question-text {
        font-size: 1rem;
    }
}
</style>

<script>
function toggleFaq(id, element) {
    const allAnswers = document.querySelectorAll('.faq-answer');
    const allQuestions = document.querySelectorAll('.faq-question');
    const currentAnswer = document.getElementById(id);
    
    // Close all other answers
    allAnswers.forEach(answer => {
        if (answer.id !== id) {
            $(answer).collapse('hide');
        }
    });
    
    // Remove active class from all questions
    allQuestions.forEach(question => {
        question.classList.remove('active');
    });
    
    // Toggle current answer
    $(currentAnswer).collapse('toggle');
    
    // Toggle active class on current question
    element.classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', function() {
    $('.collapse').on('show.bs.collapse', function() {
        const question = this.previousElementSibling;
        question.classList.add('active');
    });

    $('.collapse').on('hide.bs.collapse', function() {
        const question = this.previousElementSibling;
        question.classList.remove('active');
    });
});
</script>

@include('user.footer')
