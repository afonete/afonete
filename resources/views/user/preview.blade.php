<?php

    use Illuminate\Support\Facades\Auth;

    use App\Models\User;
    $user = Auth::user();

    $name = $user->name;
    $position=$user->has_paid_package;
    $country = $user->country;
 $id=$user->activation;
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="FONEPO Template">
    <meta name="keywords" content="FONEPO, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fonepo Affiliate || Earn money infinetely</title>
    <link rel="icon" href="{{asset('assets/front/img/small-logo.png')}}">
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('assets/front/css/jquery-ui.min.css')}}" type="text/css">
   
        <link rel="stylesheet" href="{{asset('assets/front/css/includes.css')}}">
        <link rel="stylesheet" href="{{asset('assets/front/css/booststrap_changed.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/homePage.css')}}">
</head>
<body>
        <div class="contract">
               <div class="">
                        <h1 class="main-header">Agreement (review and submit)</h1>
                        <h2 class="main-header secondary-header"> TERMS AND CONDITIONS</h2>
                        <h2 class="secondary-header">GENERAL</h2>
                        <div class="main-par"> 
                                <p class="par">The Independent Marketing affiliate ("IMA") Agreement, the Compensation Plan and the General Terms and Conditions (forming inseparable part of one document and entire agreement between the Company and its IMP) explains and governs the relationship between each IMA and COMPANY, (referred to hereafter as the "AFONEETE"). Each IMP is required to read, understand, and comply with all terms and conditions of the Agreement. The Agreement shall constitute the entire understanding of the parties. All parts of this Agreement shall apply to the Registered IMP agreement. By submitting the IMP Application/Agreement you hereby agree to all of the terms and conditions herein and by reference all legal terms and conditions.</p>

                                <p class="par">
                                       The Agreement is subject to revision by the Company from time to time at its sole discretion. The Agreement governs all aspects of the relationships between the Company and its IMPs and is available on the company. 
                                </p>

                                 <p class="par">
                                       These Terms of Service constitute the entire agreement between you and Fonepo and govern your use of the Services, superseding any prior agreements between you and FONEPO ltd with respect to the Services.
                                </p>

                                 <p class="par">
                                       THIS NON-DISCLOSURE AND CONFIDENTIALITY AGREEMENT [Here in after, this "Agreement"] is effective as of the date of show or acknowledgement of accepting the Agreement as provided through a registered office of the COMPANY, by and between:
                                </p>
                                 <p class="par">
                                     <span class="font-weight-bold">‘FONEPO ltd</span>  Here in after referred to as the (May Referred to as “FONEPO, COMPANY, ECOSYSTEM PLATFORM, “OUR”, “US”, “WE” Or Disclosing Party). (which expression shall refer to the context or meaning thereof, be deemed to mean and include its successors and assigns) of the First Part    
                                     <br>
                                     <span class="font-weight-bold">AND</span> 
                                </p>

                                <div>
                                        <div class="user-info">
                                                <div>
                                                        <span>Mr./ Mrs. / Ms.</span>
                                                        <span class="font-weight-bold">{{$name}} </span>
                                                </div>

                                                <div>
                                                        <span> Nationality,</span>
                                                        <span class="font-weight-bold">{{$country}}</span>
                                                </div>

                                                <div>
                                                       <td> <span>Holding Id</span>  </td>
                                                </div>
                                        </div>
                                </div>

                                 <p class="par">
                                      No.{{$id}}, (Here in after referred to as “IMA /YOU/ /Investor/ Shareholder/ stockholder/ User/ Founder /Cofounder/Customer/ Member/ Client/ Affiliate)”, (which expression shall mean and include its successors and permitted assigns) of the Second Part.
                                        The Company and the Cofounder/investors are each Here in after collectively referred to as "Parties" or individually as “Party”.
                                        WHEREAS

                                </p>

                                <ol class="contr-order-li">
                                        <li>
                                             <p class="par">
                                                      COMPANY has offered various information /document/explication to the User/ Founder and User/ Founder has accepted the terms and conditions of the COMPANY with the effect from and during the period of this Agreement User/ Founder will have access to the Confidential and Proprietary Information of the COMPANY and its customers, partners and other Cofounder/investor The use of Confidential Information by the User/ Founder shall be treated in Confidence. COMPANY Confidential Information, Proprietary Information, Invention, Patents, Copyrights, Trademark and other Intellectual Property Rights are important assets of the COMPANY.
                                             </p>   
                                        </li>
                                        <li>
                                             <p class="par">
                                                      COMPANY Confidential Information, shall also mean any and all information available to company and which is not available in a published form at the Effective Date, including, but not limited to, research ideas, projects, research results, research directions, patents, patent applications, and patent ideas, trade secrets, business models, business forecasts, marketing strategies, financial data, customer lists, investors, contractual relationships, manufacturing  proprietary information and documentation, ideas, schematics, sketches, models, know-how, marketing campaigns algorithms, processes, formulae whether or not specifically stated or define in subsequent paragraphs.
                                             </p>   
                                        </li>
                                        <li>
                                             <p class="par">
                                                       It is Important for the COMPANY to protect Its Intellectual Property, Confidential and Proprietary Information to the fullest extent and the User/ Founder club is willing to comply with following provisions, as part of the User/ Founder contract of service/ facility with COMPANY.
                                             </p>   
                                        </li>
                                </ol>

                                 <p class="par">
                                    NOW THEREFORE, in consideration of the service, information / document and explication  of the COFOUNDER/INVESTOR by the COMPANY , the compensation User/ Founder expects to receive, and continued  receipt and access to confidential, proprietary and trade secret information associated with User/ Founder’s position with COMPANY, the mutual covenants, agreements and obligation expressed herein  and other good and valuable consideration, the receipts and sufficiency of which are hereby acknowledged, the Cofounder/investor  and COMPANY agree as follows:
                                </p>

                                <ol class="num-li">
                                        <li class="secondary-header">
                                                <h2 class="secondary-header"> TERMS AND CONDITIONS</h2>
                                        </li>   

                                         <p class="par">
                                           To become an IMA, a new applicant is required to read and agree to the terms of the Agreement and relevant General Terms and Conditions of the Company. The application should be accurately completed in its entirety and the applicant(s), including all partners, shareholders and equity owners, must agree to the relevant General Terms and Conditions of the Company and the present IMP Agreement personally. Electronically submitted applications are considered as a received document. The Company reserves the right to reject any application at its sole discretion.
                                        </p>

                                        <ol class="alpha-li">
                                              <li>
                                                        <p class="par">
                                                                Legal Age. Any individual who is of legal age (18 years) and residing in a country where the Company is doing business is eligible to become an IMA.
                                                        </p>
   
                                              </li> 
                                              <li>
                                                        <p class="par">
                                                               ID Number. An Identification Number is to be inserted on the application. This number should be either the Individual’s Social Security Number, if applying as a business, or a government I.D., such as a passport number. Numbers or other essential information and/or supporting documentation can result in fine and/or termination.
                                                        </p>
   
                                              </li> 
                                              <li>
                                                        <p class="par">
                                                                 Legal Entity. If an Applicant is a corporation, partnership or other legal entity, all shareholder(s) of the corporate applicant, all partner(s) of the partnership or all owners of the legal entity applicant must agree to the application, a list of the names of each shareholder of the corporate Applicant, each partner of the partnership Applicant, or each owner if another legal entity must accompany the Application. All legal documentation should be submitted with the application, including but not limited to, articles of incorporation or organization, partnership agreements, regulations, , shareholder agreements, partnership agreements, and other pertinent legal information. The application can be rejected without such documentation.
                                                        </p>
   
                                              </li>  
                                               <li>
                                                        <p class="par">
                                                                  Independent Contractors. IMAs are independent contractors. They are not franchisees, joint ventures, partners, employees or agents of the Company, and are prohibited from stating or implying whether orally or in writing, otherwise. IMAs have no authority to bind the Company to any obligation. The Company is not responsible for payment or co-payment of any employee benefits. IMAs are responsible for liability, health, disability, workmen’s compensation and other insurance and for any other registration required by the laws of the country of residence of the IMA. IMAs set their own hours and determine how to conduct their COMPANY business and are responsible for their own management decisions subject to the IMA Agreement. The reference to "position" is as it applies to IMAs participation in the Compensation Plan and adherence to the IMA Agreement.D.     Independent Contractors. IMAs are independent contractors. They are not franchisees, joint ventures, partners, employees or agents of the Company, and are prohibited from stating or implying whether orally or in writing, otherwise. IMAs have no authority to bind the Company to any obligation. The Company is not responsible for payment or co-payment of any employee benefits. IMAs are responsible for liability, health, disability, workmen’s compensation and other insurance and for any other registration required by the laws of the country of residence of the IMA. IMAs set their own hours and determine how to conduct their COMPANY business and are responsible for their own management decisions subject to the IMA Agreement. The reference to "position" is as it applies to IMAs participation in the Compensation Plan and adherence to the IMA Agreement.
                                                        </p>
   
                                              </li>  
                                        </ol>

                                        <p class="par">
                                               Please read these Terms carefully as they govern your use of the Services. THESE TERMS CONTAIN IMPORTANT PROVISIONS INCLUDING AN ARBITRATION PROVISION THAT REQUIRES ALL CLAIMS TO BE RESOLVED BY WAY OF LEGALLY BINDING ARBITRATION. 
                                        </p>

                                          <p class="par">
                                               Each Partners of COMPANY is required to read, understand, and comply with all terms and conditions of the Agreement. The Agreement shall constitute the entire understanding of the parties.
                                        </p>

                                        <li class="secondary-header">
                                                <h2 class="secondary-header">DEFINITIONS AND INTERPRETATION</h2>
                                        </li>   

                                        <p class="par">
                                               <span class="two-num">2.1</span> In this Agreement (including in the recitals herein above or schedules hereto), the following words and expressions shall have, where the context so permits, the following meaning ascribed to them:
                                        </p>

                                         <p class="par">
                                               <span class="two-num">2.1.1</span> . " User/ Founder ", ,IMA", and how to use in this agreement to simplify the spelling with respect to either party means any person who directly or indirectly controls, is controlled by, or is under the common control of such parties. The term “Control” is used in the sense of the possession by a person or a group of persons acting in concert, directly or indirectly, of the right to direct or cause the direction of the management, policies, and actions of another person, whether through the board of directors or ownership of voting rights by such other Person,, contract or otherwise. A person or a group of persons acting in concert shall be deemed to be in control of a body corporate if such Person or group of Persons is in a position to appoint or appoints the majority of the directors of such body company.
                                        </p>

                                         <p class="par">
                                               <span class="two-num">2.1.2</span> <span class="font-weight-bold">“Confidential Information”</span>  shall mean information which is confidential including Proprietary Information and other information related to the business of the Company, its affiliates or any third parties with which the Company associates, whether or not such information is expressly marked or designated as confidential information and includes but is not limited to: an information of value or significance to the Company or its competitors (including potential competitors) such as:
                                        </p>

                                        <ol class="roman-li">
                                                <li>
                                                        <p class="par">
                                                                data of or about the Company or its vendors, customers, User/ Founder, advisors, mentors, service providers or consultants, in particular, contact information and sales information;
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                data about Company’s compliance with applicable law including data about licenses, permissions, approvals, permissions or consents applied for, requested by, granted to or denied to the Company or its promoters;
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                data about all filings and official submissions made by the Company to governmental authorities and the content of the discussion and communication by the Company with such authorities;
                                                        </p> 
                                                </li>


                                                <li>
                                                        <p class="par">
                                                               data related to the Company’s business, its existing and upcoming products, services, business strategy, Marketing Campaigns details, terms of engagement with its vendors or customers, pricing data, payment and refund policies and plan, business plans, cofounders/customers data, policies and plans which directly and indirectly related to the COMPANY.
                                                        </p> 
                                                </li>


                                                <li>
                                                        <p class="par">
                                                                any data, documents, sketches, designs, plans, drawings, photographs, reports, communication, technical information, information about Intellectual Property Rights, user information, compilation, subscription details, asset information, know-how, research and development, internal policies;
                                                        </p> 
                                                </li>


                                                <li>
                                                        <p class="par">
                                                                Any information related to the Company’s technology, software, hardware, code, design, business strategy, business plan, internal systems, and business architecture;
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                               financial data, in particular, concerning budgets, fees and revenue calculations, sales figures, bonus plans, financial statements, profit expectations and inventories of the Company;
                                                        </p> 
                                                </li>


                                                <li>
                                                        <p class="par">
                                                                training data, particularly documents, videos, webinars, photographs, website data, processes, multimedia files, presentations and any such training resources that the User/ Founder gains access to during his association with the Company;
                                                        </p> 
                                                </li>


                                                <li>
                                                        <p class="par">
                                                                security information (including passwords, login credentials) used to access any resource owned or operated by the Company, its affiliates, clients or third-party agents;
                                                        </p> 
                                                </li>


                                                <li>
                                                        <p class="par">
                                                               client or user data, user credits, user analytics, user preferences, feedback information;
                                                        </p> 
                                                        <ol class="alpha-li-2">
                                                                <li>
                                                                        <p class="par">
                                                                                any information which may be reasonably understood by its nature, or by the context of its disclosure, to be confidential; and
                                                                        </p>
                                                                </li>
                                                                <li>
                                                                        <p class="par">
                                                                                any information derived from any of the above-mentioned information; and
                                                                        </p>
                                                                </li>
                                                                <li>
                                                                        <p class="par">
                                                                                any information which may be reasonably understood by its nature, or by the context of its disclosure, to be confidential; andoriginal information supplied by the Company or information provided to the Company by third parties which the Company is obligated to keep confidential
                                                                        </p>
                                                                </li>
                                                        </ol>
                                                </li>


                                                <li>
                                                        <p class="par">
                                                                prohibition of postings about other companies or other business opportunities or programs, including their links;
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                 prohibition of sharing posting and comment that reflects a race, sex, cast, religion, political agenda, team competition and cross-recruiting as well, 
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                prohibition of vigorous and impassioned sharing of ideas, negative languages, webinar/videos/community/data of the COMPANY
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                participate in constructive online dialogues and use our best judgment when posting to external or internal social media.
                                                        </p> 
                                                </li>
                                               
                                        </ol>

                                        <p class="par">
                                               <span class="two-num">2.1.3</span> <span class="font-weight-bold">“Intellectual Property Rights” </span>  means and includes, without limitation, any patents, copyrights, trademarks, trade secrets, service marks, database right, design right, moral right or any other property rights (in each case, whether registered or not and including applications for registration, if any) that grant similar rights as the foregoing, anywhere in the world.
                                        </p>

                                         <p class="par">
                                               <span class="two-num">2.1.4</span> <span class="font-weight-bold">“Company Intellectual Property” </span> means any Proprietary Information created, conceived, developed, and improved by the COMPANY.
                                        </p>
                                         <p class="par">
                                               <span class="two-num">2.1.5</span> <span class="font-weight-bold"> “Proprietary Information” </span>means and includes, but is not limited to, Company Intellectual Property information about software programs, software designs, software architectures, source code, object code, algorithms, trade secrets, formulae, data, designs,  technical know-how, domain names, processes, applications, ideas, user/ founder/customers data and records, techniques, documents, notes, presentations, works of authorship, business plans, customer lists, user information, vendor data, customer data, operational data, terms of compensation and performance levels of Cofounder/Customer, Marketing Campaigns and other information concerning the actual or anticipated business, research or development, prices and pricing structures, marketing and sales information, competitive analysis, and any information and materials relating thereto, or which is received in confidence by or for the Company or its Affiliates from any other person, whether or not it is in written or permanent form.
                                        </p>

                                        <p class="par">
                                               <span class="two-num">2.1.6</span> <span class="font-weight-bold"> Registration Suspension Policy</span>The Notice of Warning will contain instructions to contact Fonepo ltd via an account-associated email at support@fonepo.com citing time limits.  If offending Founder does not respond to the Notice of Warning, does not respond to the Notice of Warning with allotted time, or does not produce vindicating evidence or explanation through valid a current, valid e-mail address that is associated with the offending Founders account, the Founder’s account [with full registration] shall be terminated without further notice.
                                        </p>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header">Investment </h2>
                                        </li>  

                                         <p class="par">
                                                <span class="font-weight-bold"> Registration Suspension PolicyUser/ Founder voluntarily agrees to invest in COMPANY as a start-up company in this projects
                                                she or her is investing same amount in this   $100, $200 
                                                </span>
                                        </p>

                                        <p class="par">
                                              The IMA voluntarily agreed that it will be reimbursed based  to support  company to building  this projects .her/she   has signed this agreement.
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header">Advertising </h2>
                                        </li>  

                                          <p class="par">
                                              By using fonepo you agree to receive advertising from numerous third party advertisers. Attempts to block or otherwise bypass the advertising will be considered a breach of these terms.
                                        </p>
                                        <p class="par">
                                            Trademarks/Service Marks. IMAs shall not advertise FONEPO product or business opportunity in any way other than by use of authorized advertising or promotional materials made available to the IMA by the Company.
                                        </p>

                                        <ol class="alpha-li-2">
                                                <li>
                                                        <p class="par">
                                                                IMAs are prohibited from using Company’s trademarks, service marks, trade names, slogans, symbols, and color schemes in advertising in a manner that would suggest or imply that they are employed by or are agents of the Company. All advertisements must provide the name of an IMA only. IMAs shall not make any representations as to potential income to be received by a prospective IMA.
                                                        </p>
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                No Reproduction. FONEPO materials, whether printed, on film or produced by audio or video recording are copyrighted and may not be reproduced in whole or in part by IMAs or any other person unless authorized in writing by the Company.
                                                        </p>
                                                </li>

                                                <li>
                                                        <p class="par">
                                                               No Distribution. IMAs may not produce, use or distribute any information relative to the contents, characteristics, or properties of COMPANY products which has not been provided directly by the Company. This includes but is not limited to print, audio or online media.
                                                        </p>
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                Further Restrictions. The Company prohibits the use of its trademarks, service marks, trade names, slogans or symbols or any of its product trade names or any copyrighted materials through telephonic devices, including computer networks, facsimile machines or other automatic calling devices for the purpose of soliciting potential IMAs or potential members.
                                                        </p>
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                Repackaging. IMA may not re-label, repackage, or modify FONEPO’s packaged or virtual training materials in any way.
                                                        </p>

                                                        <ol class="roman-li-2">
                                                                <li>
                                                                        <p class="par">
                                                                              Internet Policy. IMAs may not advertise or promote their IMA business or Company’s business, products or Global Compensation Plan or use Company’s name in any electronic media or transmission, including on the Internet via web sites or otherwise, without the prior written approval of the Company, whose approval may be withheld at its sole discretion. If written approval is given, IMAs must abide by the guidelines set forth by the Company, including but not limited to the following:  
                                                                        </p>

                                                                        <ol>
                                                                                <li>
                                                                                        <p class="par">
                                                                                                IMAs shall not make offers or solicitations in the guise of research, surveys or informal communication, when the real intent is to sell products or services or sponsor IMAs;
                                                                                        </p>
                                                                                </li>

                                                                                 <li>
                                                                                        <p class="par">
                                                                                               IMAs operating on-line websites, whether or not they collect personal information from individual members, shall disclose to the member in a prominent place on the website how the member's information will be used and must follow any laws regulating the handling of personal data;
                                                                                        </p>
                                                                                </li>


                                                                                 <li>
                                                                                        <p class="par">
                                                                                                IMAs sharing personal information collected on-line should provide individual members with an opportunity to prohibit the dissemination of such information, and if any member requests that his or her personal information not be shared, IMAs shall refrain from sharing such information;
                                                                                        </p>
                                                                                </li>

                                                                                  <li>
                                                                                        <p class="par">
                                                                                               IMAs shall provide individual members the option to terminate any further communication between the IMAs and the member and if any member requests that an IMA cease communication, the IMA should immediately stop communicating upon such request;
                                                                                        </p>
                                                                                </li>

                                                                                  <li>
                                                                                        <p class="par">
                                                                                                IMAs must abide by all laws and regulations regarding electronic communications, including but limited to any provision requiring prior consent for unsolicited contacts via electronic media;
                                                                                        </p>
                                                                                </li>

                                                                                <li>
                                                                                        <p class="par">
                                                                                                IMAs may not distribute content by use of distribution lists or to any person who has not given specific permission to be included in such a process; spamming or distribution of chain letters or junk mail is not allowed;
                                                                                        </p>
                                                                                </li>

                                                                                <li>
                                                                                        <p class="par">
                                                                                               IMAs may not distribute content that is unlawful, harassing, libelous, slanderous, abusive, threatening, harmful, vulgar, obscene, or otherwise objectionable material or which could otherwise give rise to civil liability or otherwise violate any applicable local, state, national or international law or regulation; and
                                                                                        </p>
                                                                                </li><li>
                                                                                        <p class="par">
                                                                                               IMAs may not send bulk unsolicited e-mails to persons who have not requested information. Spam is strictly prohibited
                                                                                        </p>
                                                                                </li>
                                                                              
                                                                        </ol>
                                                                </li>
                                                        </ol>
                                                </li>
                                        </ol>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header">IMA REBOOT PACKAGE REFERRER POLICY </h2>
                                        </li>  

                                        <ol class="alpha-li-2">
                                                <li>
                                                        <p class="par">
                                                                Every new IMA has a Referring IMA. Each new prospect has the right to choose who he/she buys his/her reboots and Rewards Package from. An IMA shall not unduly influence or in any way entice prospects with representations as to possible income or business development, or payment of any compensation by an IMA or the Company.
                                                        </p>
                                                </li>

                                                 <li>
                                                        <p class="par">
                                                               Dispute. The Company recognizes the Referring IMA as the IMA shown on the first entered original IMA Application completed, dated, and electronically entered into Company’s database. 
                                                </li>

                                                 <li>
                                                        <p class="par">
                                                                Reboot. IMAs who enroll other IMAs and sell reboot Packages must thereafter use their best efforts to provide an on-going basis, bona fide supervision and training of these IMAs and their sales group. This should include ongoing contact, communication, encouragement and support of his/her sales organization. IMA’s should encourage their IMA sales groups to complete the Company’s reboot Packages that they purchase.
                                                        </p>
                                                </li>
                                        </ol>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header">Modifications to Services:  </h2>
                                        </li>  

                                        <p class="par">
                                               <span class="font-weight-bold">COMPANY</span>reserves the right to modify, suspend or discontinue, temporarily or permanently, the Services (or any part thereof) at any time and for any reason, with or without notice, and without any liability to you or to any third party for any claims, damages, costs, or losses resulting therefrom.
                                        </p>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header">CONFIDENTIALITY OBLIGATIONS </h2>
                                        </li> 

                                         <p class="par">
                                                The IMA agrees and acknowledges that during the term of this Agreement with the Company the IMA shall have access to Confidential Information through oral, visual, electronic or written means.
                                        </p>

                                         <p class="par">
                                              The IMA/ Cofounder/investor understands and acknowledges that the Confidential Information is of immense value to the Company and its Affiliates and/or its present, past or prospective clients. The User/ Founder understands that any use or disclosure of such Confidential Information including any inadvertent disclosure can cause immense and irreparable harm, loss, damage and injury to the Company and its Affiliates and its reputation and hence undertakes to keep such Confidential Information confidential. 
                                        </p>

                                         <p class="par">
                                               
                                               The User/ Founder agrees and undertakes that at all times during the term of this Agreement and thereafter on termination of this Agreement for whatever reason to hold in the strictest confidence, and not to use, except for the benefit of the Company and its Affiliates, and absolutely refrain from in any manner divulging, discussing, disclosing, or otherwise releasing, the Confidential Information to any third party or in any manner directly or indirectly using the Confidential Information without the written authorization of the Company. 
                                        </p>

                                         <p class="par">
                                               The User/ Founder recognizes that the Company and its Affiliates have received and, in the future, will receive from third parties, information that would be confidential and proprietary in nature to such third parties, during the term of Agreement.
                                        </p>

                                         <p class="par">
                                               The User/ Founder further undertakes not to make copies of, or disseminate, such Confidential Information except as authorized by the Company. Nothing contained in this Agreement shall be construed as granting or conferring any rights either as a license or otherwise in the Confidential Information. The User/ Founder  shall not claim or represent to hold any interest by way of COMPANYship, assignment or otherwise in the Confidential Information. 
                                        </p>


                                          <li class="secondary-header">
                                                <h2 class="secondary-header"> INSIDER TRADING AND NON PUBLIC INFORMATION </h2>
                                        </li> 


                                        <p class="par">
                                               IMA/Member/ Client/Customer, his/her family and relatives are prohibited under Company policy as well as by Law from purchasing, selling and dealing in Company investment/ marketing campaigns, strategy, plan/ policies and platforms or the investment of any other company, such as existing or potential customers or suppliers, directly or indirectly, on the basis of material non-public information. All non-public information about the Company should be considered confidential information. To use non-public information for personal financial benefit or to “tip” others who might make an investment decision on the basis of this information is unethical, against Company policy, and illegal. IMA or ‘Cofounder/Member/ Client/Customer his/her family and relatives do not have right to engaged in term of employment whether (full time/part time/consultant and other)’.
                                        </p>

                                        <li class="secondary-header">
                                                <h2 class="secondary-header"> TAXES AND GOVERNMENTAL REPORTS</h2>
                                        </li> 

                                        <ol class="alpha-li-2">
                                                <li>
                                                        <p class="par">
                                                                Taxes. All IMAs are personally responsible for all taxes due on any earnings from the Company or on sales proceeds or earnings from selling COMPNAY products or service. The Company will provide a record of any and all moneys paid by the Company to each IMA and will issue and file such reports to governmental agencies or others as may be required by law. IMAs will not be treated as an employee of the Company for any income tax purposes. To safeguard that no tax evasion can occur, the IMA must give the Company personal identification information in their country of residence to which the Company can make payments through a designated payment facilitator of any amount due by the Company to the IMA.
                                                        </p>
                                                </li>
                                                <li>
                                                        <p class="par">
                                                                Sales Tax. The Company has its’ home office and will not be collecting sales tax at the time of purchase from any residents outside of the areas its location requires it to. No sales tax or VAT is foreseen on sales outside of the Company’s primary location. If such tax would be levied, the IMA will immediately inform the Company, which will take appropriate action, including remitting, if needed the sales tax/VAT to the appropriate authority.
                                                        </p>
                                                </li>
                                        </ol>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> RESTRICTIONS</h2>
                                        </li> 

                                        <ol class="alpha-li-2">
                                                <li>
                                                        <p class="par">
                                                                Except for the express written consent of COMPANY, User/ Founder agrees:
                                                        </p>
                                                       
                                                </li>

                                                 <ol class="num-li-2">
                                                        <li>
                                                                <p class="par">
                                                                      Not to use or disclose to another person or entity any confidential information of COMPANY.  
                                                                </p>
                                                        </li>

                                                         <li>
                                                                <p class="par">
                                                                      Not to make, or cause to be made, any copies, facsimiles or other reproductions including data files of any documents containing confidential information of COMPANY; and
                                                                </p>
                                                        </li>

                                                         <li>
                                                                <p class="par">
                                                                      To use all other reasonable means to maintain the secrecy and confidentiality of the confidential information of COMPANY.
                                                                </p>
                                                        </li>
                                                </ol>

                                                <li>
                                                        <p class="par">
                                                         User/ Founder further agrees, at the request of COMPANY:
                                                        To immediately return to COMPANY all items in the possession of User/ Founder which relate to or which disclose in whole or in part any confidential information of COMPANY; and To refrain from using or disclosing to any other person or entity any confidential information of COMPANY.

                                                        </p>
                                                       
                                                </li>

                                        </ol>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> REPRESENTATIONS AND WARRANTIES</h2>
                                        </li> 
                                        <p class="par">The User/ Founder represents and warrants that:</p>

                                        <ol class="num-li ml-5">
                                                <li>
                                                        <p class="par">
                                                          The User/ Founder’s performance of all terms of this Agreement and as a User/ Founder of the COMPANY does not and will not breach any prior agreement with others keep in confidential information acquired by the User/ Founder in Confidence or in trust prior to the registration.
                                                        </p>
                                                </li>

                                                <li>
                                                        <p class="par">
                                                           The User/ Founder has not and shall not enter into agreement, either written or oral, in conflict with this agreement.
                                                        </p>
                                                </li>
                                        </ol>
                                         <li class="secondary-header">
                                                <h2 class="secondary-header">COVENANT NOT TO SUE</h2>
                                        </li> 

                                        <p class="par">
                                                Cofounder/Customer shall not institute any action or suit at law or in equity against COMPANY, nor institute, prosecute or in any way aid in the institution or prosecution of any claim, demand, action, or cause of action arising out of the CONFIDENTIAL INFORMATION or any INTELLECTUAL PROPERTY thereof, including but not limited to, claim, demand, action, or cause of action for invalidating any INTELLECTUAL PROPERTY and CONFIDENTIAL INFORMATION of the COMPANY.
                                        </p>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> PAYMENT POLICY& METHODS ACCEPTED</h2>
                                        </li> 

                                        <p class="par">
                                               User/ Founder shall eligible for our various service/facilities after making valid/authorized payment (through the modes as explained/mentioned in oral). Payment policies and structure are defined by Company at its sole discretion and it will change periodically as per business strategies and plans (as required)
                                        </p>

                                         <p class="par">
                                              Currently for the comfort of its clients and partners the Company accepts various payment methods for the purchase of goods, main of which are as follows:
                                                Bank transfer and mobile money, cheque, cash,
                                                The COMPANY warrants that the above stated list of payment methods may be modified from time to time.

                                        </p>

                                         <p class="par">
                                               The IMA has agreed that it will receive the benefits based on the investment it has made in the company will be paid within three hundred sixty days (360) but no beyond seven hundred twenty days of work without excluding weekends and public holidays in Rwanda. 
                                        </p>

                                         <p class="par">
                                               You expressly understand and agree that, to the fullest extent permitted by applicable law, neither COMPANY nor its affiliates will be liable for any (a) indirect, incidental, special, consequential, punitive or exemplary damages, (b) damages for loss of profits, (c) damages for loss of goodwill, (d) damages for loss of use, (e) loss or corruption of data, or (f) other intangible losses (even if company  has been advised of the possibility of such damages), whether based on contract, tort, negligence, strict liability or otherwise, resulting from (i) the use or the inability to use the services; (ii) the cost of procurement of substitute goods and services resulting from any goods, data, information or services purchased or obtained or messages received or transactions entered into through or from the services; (iii) any promotions and related prizes or rewards made available through the services; (iv) unauthorized access to or alteration of your transmissions or data; (v) statements or conduct of any third party on the services; or (vi) any other matter relating to the services. To the fullest extent permitted by applicable law, in no event will FONEPO's total liability to you for all damages, losses (including contract, negligence, statutory liability or otherwise) or causes of action exceed the amount you have paid FONEPO in the last six (6) months.
                                        </p>

                                         <p class="par">
                                              Some jurisdictions do not allow the exclusion of certain warranties or the limitation or exclusion of liability for incidental or consequential damages. Accordingly, some of the limitations set forth above may not apply to you. If you are dissatisfied with any portion of the services or with these terms of service, your sole and exclusive remedy is to discontinue use of the services.  
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> REFUND POLICY</h2>
                                        </li> 

                                        <p class="par">
                                               Active User/ Founder shall be entitled to request, and eligible to receive, a full refund within the six months (6months) from the date of the assigned of the agreement for which a refund is being requested. 
                                        </p>

                                        <p class="par">
                                               CANCELLATIONS
                                        </p>

                                         <p class="par">
                                               All refund requests must be made within 14 days of enrollment/purchase in accordance with the above stated conditions. The return request will deactivate the initial order instructions and it will delete the IMA’s unique identification (user name) for the REBOOT and Rewards Package. This cancellation policy is designed to protect the ALS reboots and marketing  material because it is proprietary intellectual property. The IMA’s activation and position in the compensation system will be cancelled. Any commissions paid to returne will be deducted from the refund amount. Submit cancellations at support department and please provide IMA's username, payment proof and date of purchase.
                                        </p>

                                         <li class="secondary-header">
                                                <h2 class="secondary-header">LIMITED RETURN POLICY</h2>
                                        </li> 

                                         <p class="par">
                                               IMAs are not required to purchase.
                                        </p>

                                        <p class="par">
                                              The IMA may return purchased FONEPO products in the following circumstances (excluding personalized or customized items):
                                        </p>

                                        <p class="par">
                                               The purchased package may only be refunded to the IMA in case he/she have not logged in for the period of 14 (fourteen) days, starting from the date of submission of the application. No refunds will be made after the IMA has logged into his account. By logging into an IMA account the Company considers that the IMA is accepting the Company’s conditions and no refunds shall be made.
                                        </p>


                                        <p class="par">
                                              The Company will process the return promptly upon verifying that the IMA is eligible for a refund. In most cases, the IMA should expect to receive the refund within 30 working days of receipt of the products by the Company. The IMA hereby confirms that he understands that the Company return policy will NOT cover situations where the IMA has already received commission payouts, or other benefits, as a result of using the FONEPO’s products and/or services.
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header">DAMAGES AND SPECIFIC PERFORMANCE</h2>
                                        </li> 

                                         <p class="par">
                                              User/ Founder agrees that should cofounder/customer breach any portion contained in this agreement that company would suffer irreparable harm and company would be without adequate remedy at law and that company may obtain injunctive relief, including specific performance of the agreement, as well as monetary award for damages suffered by company for cofounder/customer’s breach of this agreement.
                                        </p>

                                         <p class="par">
                                              To the extent permitted by law, the company and its imp, officers, directors, employees and other representatives shall not be liable for, and imps hereby release the foregoing from, and waive any claim for loss of profit, incidental, special, consequential or exemplary damages which may arise out of any claim whatsoever relating to company’s performance, non-performance, act or omission with respect to the business relationship or other matters between any imp and the company, whether sounding in contract, tort or strict liability. Furthermore, it is agreed that any damages to an imp shall not exceed, and is hereby expressely limited to the amount of unsold company’s programs, services and/or products owned by the imp and any commissions owned by the imp.
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> Relationship of the Parties.</h2>
                                        </li> 

                                         <p class="par">
                                               The Contractor is acting as an independent contractor in providing the services under this agreement, not as an employee, agent or servant. The parties agree that this agreement does not create a joint venture or partnership between them.
                                        </p>

                                        <li class="secondary-header">
                                                <h2 class="secondary-header"> NOTICE.</h2>
                                        </li> 

                                         <p class="par">
                                               Any Notice or communication required or allowed under this agreement, regardless of the communication method, shall take effect upon actual delivery of the notice at the legal domicile, residence, telephone, voice, or mailing address of the receiving party. If party alters its notification address or mailing address, it shall notify the other party of its new address within 3 (three) days after the alteration, otherwise, the defaulting party shall be held responsible for all consequent legal liabilities. In cases where no legal domicile, residence or mailing address of the receiving party is known or available, attempts to contact that party will be made via all means available to include digital media and social platforms.  In case where no contact can be made through reasonable attempts, the contacting party shall not be held responsible for consequent liabilities arising from inability to contact receiving party.
                                        </p>

                                          <p class="par">
                                               Correspondences served through facsimile, e-mail, or any other simultaneous electronic valid and available medium shall enjoy the same legal effect as the traditionally signed documents in terms of evidence and legal value.
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header">  Consequences of cancellation:</h2>
                                        </li> 

                                         <p class="par">
                                              The IMP shall, as long as it wishes to terminate this agreement, be executed within 186 working days of  the date on which the agreement is signed without   weekend, holiday Rwanda.
                                        </p>

                                        <p class="par">
                                              If the agreement is canceled, any benefits, payments, and/or services received by either party must be returned according to the refund- and cancellation policy stated under the General Terms and Conditions of the Company, to the other, together with any benefits derived from the same. At this time, we expressly indicate that the services are considered to have been used once the IMA has accessed the services of information about projects or business plan of project. If an IMA can not return the services received either in full or in part, or an IMA can only return them in a condition worse than that in which they were received, the specified IMA must reimburse for the loss in value. All payment refunds must be made within 186 days. For the IMA, the period begins when revocation notice is sent, and for the Company upon receipt of the same. Provided that said cancelation of the IMA was more than 12 months earlier and that the IMP has not since performed any activities for the The Company reserves the right to not refund IMA’s for business plan Packages/Events of its proprietary information that IMA has already used or participated in.
                                        </p>

                                        <p class="par">
                                             The IMA agrees that if there is any reason for the termination of the agreement, the company will only repay the investment given, without any kind interest add on, but will not pay any interest on it or not any add benefits and will not pay any interest in the contract shown and subject to the terms and days provided for in the termination agreement or cancelled. any loss no one will blame it. and no other benefits waits it will either in the near future or in the long run, and no any kind of interest direct or indirect will given. and that there is no other consequence that will cause the company will not be liable for any damages or any loss, damages arising out of or in connection with any of the transaction’s investment or for any reason whatsoever. And that there is no other consequence that would cause the company in direct or indirect
                                        </p>


                                        <li class="secondary-header">
                                                <h2 class="secondary-header"> NON-WAIVER PROVISION</h2>
                                        </li> 

                                         <p class="par">
                                              Failure of the Company to exercise any right stated in the present IMA Agreement shall not constitute a waiver of Company’s right to demand exact compliance therewith. Waiver by the Company of any breach of any provision of the present IMA Agreements shall not constitute a waiver of any prior, concurrent, or subsequent breach by the IMA. An authorized officer of the Company must issue the Waiver in writing.
                                        </p>

                                        <li class="secondary-header">
                                                <h2 class="secondary-header">SEVERABILITY</h2>
                                        </li> 

                                         <p class="par">
                                              If under any applicable and binding law or rule of any applicable jurisdiction, any provision of the present IMA Agreement is held to be invalid or enforceable, the Company shall have the right to modify the invalid or unenforceable provision or any portion thereof, to the extent required to be valid and enforceable, and the IMP shall be bound by any such modification. The modification will be effective only in the jurisdiction in which it is required.
                                        </p>
                                          <p class="par">
                                             Should a court of competent jurisdiction find that any portion of this Agreement is invalid, illegal, or unenforceable, the remaining provisions shall remain in full force and effect, and the parties shall use reasonable efforts to substitute a valid, legal, and enforceable provision that implements purposes of the provision so held invalid, illegal, or unenforceable to any extent permissible under the law.
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header">NO WARRANTIES</h2>
                                        </li> 

                                         <p class="par">
                                             The company hereby disclaims all warranties. The company makes no representations about the suitability, reliability, availability, timeliness, and accuracy of the site, success of projects or business, the service or the content contained on the site for any purpose. To the maximum extent permitted by applicable law, the site and all such content, services and products are provided "as is," "with all faults," and "as available." we disclaim all warranties, express and implied, arising out of, or in connection with, the site,  business ,service and content, including, but not limited to the warranties of non-infringement, merchantability, and fitness for a particular purpose and those arising by law, statute, usage of trade or service of dealing and any liability with regard to the site, content and services and any actions resulting from imp participation in any service.
                                        </p>
                                          <p class="par">
                                            Imp’s use of the site, service and content is at his/her sole risk. Although our content may be updated from time to time, it may be out of date and/or may contain inaccuracies or typographical errors. We are not responsible for the imp’s inability or failure (for any reason) to access the site or content or otherwise use or receive information or service from or regarding the site, content, or imp’s purchases from the company. The company does warrant that to the projects, site or service will be compatible with any hardware or software systems or that the site or service will be uninterrupted or error free.
                                        </p>

                                         <p class="par">
                                              The company is not responsible or liable for maintaining any member data or for the deletion, corruption, destruction, damage, loss or failure of any member data or for any third-party access to any member data.
                                        </p>
                                          <p class="par">
                                            The company is not responsible and may not be held liable for any additional bank fees, taxes and currency exchange rates, that may result in any amounts to be added to the account of the respective imp.
                                        </p>

                                         <p class="par">
                                             The IMA has agreed that the expected repayment period of the first installment for a period of one year may be extended but not more than 2 years as it has been shown in this agreement to be possible for any reason Couse for late payment.
                                        </p>
                                          <p class="par">
                                            The Cofounder / customer agrees that in the event of a bankruptcy or sudden loss of any kind due to unusual circumstances resulting in a failure to pay well the fees as set forth in this agreement there will be auto renew agreement so that the company will be able to pay for the refund and no any further benefits addon refund .in the event of a delay in paying the company and will not be liable or no add  charge of compensated..   
                                        </p>

                                         <p class="par">
                                              The company makes no warranty or representation as to the level of success, if any, individuals may achieve by using any of the company’s services or products. Individual results may vary and depend on many factors including an individual's specific financial situation, efforts and actions.
                                        </p>



                                         <li class="secondary-header">
                                                <h2 class="secondary-header">LIMITED LIABILITY</h2>
                                        </li> 

                                         <p class="par">
                                             To the maximum extent permitted by law, the company and it’s affiliated parties shall have no liability whatsoever for imp’s use of any content or other information or service related to the site, service or products and shall not be liable for any direct, indirect, special, incidental, or consequential damages (including, but not limited to, damages for loss of business, loss of profits, or litigation), (i) arising from any decision made or action taken by the imp in reliance upon the content or our products or service, (ii) arising out of or in any way connected with the use or performance of the site or content, or with the delay or inability to use the site, content, or related service, or from the use or misuse of any information, products, services, related graphics, and content obtained through the site, (iii) any incorrect or missing information or data, or (iv) otherwise arising out or resulting from loss of the imp’s data or information, whether based on breach of contract, breach of warranty, tort (including, but not limited to, negligence), or otherwise, even if advised of the possibility of such damages. The company’s maximum liability, if any, for any loss or damage relating to or arising out of the use of the site, services, products or any content will not exceed the lesser or actual damages or the charges paid by imp to the company for the auto-ship amount for a period of two months.
                                        </p>

                                         <p class="par">
                                             The company is not responsible for interrupted, inaccessible or unavailable networks, servers, satellites, internet service providers, websites, or other connections, or for miscommunications, failed, jumbled, scrambled, delayed, or misdirected computer, telephone or cable transmissions, or for any technical malfunctions, failures or difficulties.
                                        </p>

                                         <p class="par">
                                             The above limitations and exclusions shall apply to the imp to the fullest extent that applicable law permits, in all actions of any kind, whether based on contract, tort (including, without limitation, negligence) or any other legal or equitable theory. Any clause declared invalid shall be deemed severable and not affect the validity or enforceability of the remainder of the present imp agreement.

                                         <p class="par">
                                             The company is not responsible for interrupted, inaccessible or unavailable networks, servers, satellites, internet service providers, websites, or other connections, or for miscommunications, failed, jumbled, scrambled, delayed, or misdirected computer, telephone or cable transmissions, or for any technical malfunctions, failures or difficulties.
                                        </p>

                                         <p class="par">
                                             The above limitations and exclusions shall apply to the Cofounder / customer  to the fullest extent that applicable law permits, in all actions of any kind, whether based on contract, tort (including, without limitation, negligence) or any other legal or equitable theory. Any clause declared invalid shall be deemed severable and not affect the validity or enforceability of the remainder of the present the Cofounder / customer agreement.
                                        </p>

                                         <p class="par">
                                             Every Cofounder / customer who is a member of the IMP has no any right to  to make decision relating to the decision of the entrant or employee or  the inter subcontractor or the issuance of any shares to be made, without prejudice to any right to comment or interrogation related to it in the  company .
                                        </p>

                                        <p class="par">
                                            This Agreement shall be terminated in any event that the Company intends to suspend its operations for any reason, or in the event of any loss, in the event of a Disaster, War, Epidemic, or other strategy. The government will suspend or interfere with the normal functioning of the company in the event that these problems occur or the cofounder / customer will be reimbursed for all the money he had given to the company but no any interest has been add or paid to him incurred
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> Insurance</h2>
                                        </li> 

                                         <p class="par">
                                             The Contractor is responsible for all any insurance coverage that you believe is necessary to protect, business, technician, and persons in or, including coverage for personal injury, death, and other any property damage.  The Company does not guaranty that they will prevent personal injury, unauthorized entrances or fire, and smoke, and any other kind of damage. the company is not responsible for any kind of insurance and will not be held liable or any requested or for any consequences arising out of these activities.
                                        </p>

                                        <p class="par">
                                             Fonepo accepts no responsibility for any kind costs connected to the medical insurance or any kind of insurance of Cofounder /customer.FONEPO does not cover the any kind costs of  medical evacuation of  IMA; or any other kind of damage, accident, fire, loss, or any kind of. They will not be held accountable or sued or prosecuted
                                        </p>



                                         <li class="secondary-header">
                                                <h2 class="secondary-header">     TERM AND TERMINATION:</h2>
                                        </li> 

                                         <p class="par">
                                             - This Agreement shall be effective on fifteen days after the date of signing this Agreement (the “Effective Date”).
                                        </p>

                                        <ol class="num-li">
                                                <li>
                                                        <p class="par">
                                                                This Agreement shall be effective on the date of commencement of service of YOU / User/ Founder/ Customer with the Us and continue in force till 10 (Ten) Years after the date of termination/end of the Agreement for whatsoever reason.
                                                        </p>
                                                </li>

                                                <li>
                                                        <p class="par">
                                                                This Agreement shall run for the whole period where the parties are doing business with each other however, and as mentioned before, Receiving Party’s obligations under this Agreement shall survive for a period of ten (10) years from the termination or expiration of this Agreement between the Parties and shall be binding upon the Receiving Party's heirs, successors, and assigns upon termination or expiration of this Agreement, or upon written request of the Disclosing Party.
                                                        </p>
                                                </li>

                                                 <li>
                                                        <p class="par">
                                                                This Agreement may not be renewed or otherwise extended unless by the written renewal/extension agreement signed between the Parties and this Agreement may only be amended or modified by a written document, signed by both Parties.
                                                        </p>
                                                </li>
                                        </ol>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> CHANGES IN IMA STATUS</h2>
                                        </li> 
                                        <ol class="alpha-li-2">
                                                <li>
                                                        <p class="par">
                                                               Death. Upon the death of an IMA, the rights and responsibilities of the IMA are passed on to the rightful heir(s) as determined by a court of competent jurisdiction. The heir(s) must also confirm in writing within 6 months of the previous IMA’s death that he or she shall be bound by the terms and conditions of the IMA Agreement.
                                                        </p> 
                                                </li>

                                                <li>
                                                        <p class="par">
                                                               Divorce. Upon divorce, The Company must be notified as to which former spouse will assume ownership of the IMA position as determined by a court of competent jurisdiction. A change in the ownership of the IMA position will not take place until the Company receives a copy of the divorce documentation. Should the party who does not assume ownership of the IMA position desire to remain an IMA; he/she may do so by submitting a new IMA Application at the time the divorce documentation is submitted to the Company. He/she shall then be entered as a new IMA in accordance with the enrolment policy of the Company.
                                                       </p>
                                                </li>


                                                  <li>
                                                        <p class="par">
                                                               Marriage. If two (2) existing IMAs marry, they may maintain their separate IMA positions.
                                                       </p>
                                                </li>


                                                  <li>
                                                        <p class="par">
                                                               Dissolution of Corporate or Partnership IMA position. Upon the dissolution of a corporation or termination of a partnership, which owns the IMA position, the ownership of the IMA position will be transferred pursuant to the IMA Agreement among the shareholders or partners or upon order of a court of competent jurisdiction upon written notification to the Company. If one or more of the partners or shareholders in an IMA position terminates his/her on-going relationship with the Company by leaving the partnership or disposing of his/her share of equity holdings, such parties, including the departing party, shall continue to be bound by the terms of the IMA Agreement. If a dispute arises over the disposition of the partnership interest, or assets, or share holdings or corporate assets, or the income from the IMA position, the Company may suspend the IMA position and hold all commissions until the dispute over the disposition is resolved by agreement between the parties or by an order from a court of competent jurisdiction.
                                                       </p>
                                                </li>


                                                  <li>
                                                        <p class="par">
                                                                Sale of an IMA position or any rights, direct or indirect, relating to an IMA position may not be transferred by the IMA without prior written approval from the Company, in its sole and absolute discretion. No sale will be approved unless it includes a covenant by the seller not to solicit his/her prior Group of IMAs for at least six (6) months after the effective date of the sale.
                                                       </p>
                                                        <p class="par">
                                                               An IMA position or any right thereto which is under suspension, on probation, or subject to any disciplinary action or any investigation by or on behalf of the Company, may not be sold or otherwise transferred while such condition continues.
                                                       </p>

                                                       <p class="par">
                                                               The Purchase and Sale Agreement must include a provision in which the parties agree upon the ownership of the inventory of the IMA position upon the sale. The Company shall not grant a refund on inventory from a person who sold his/her interest in an IMA position
                                                       </p>

                                                       <p class="par">
                                                               The seller may not reapply or purchase another IMA position for a period of twelve (12) months, either as an individual, partnership or corporation or other legal entity.
                                                       </p>

                                                       <p class="par">
                                                              The purchaser of an IMA position shall be responsible for all acts or omissions of the seller in contravention of the IMA Agreement for a period of six (6) months after the date of Company's approval of the sale or transfer. For purposes of this provision, the seller will be required to continue to comply with all post-termination obligations of the IMA Agreement.
                                                       </p>
                                                </li>

                                                  <li>
                                                        <p class="par">
                                                              Mergers; Addition of Co-IMAs, Partners, Shareholders and/or Owners. Mergers will be permitted only between enroller and its first level. The Company reserves the right in its sole and absolute discretion to approve or disapprove any proposed merger or admission of Co-IMAs, partners, shareholders or other owners. The admission of a Co-IMA, partner, shareholder or other owner must create a bona fide business relationship and must not involve the addition of inactive persons or of entities or other persons or entities which "pass through" income to others.
                                                       </p>
                                                </li>

                                                  <li>
                                                        <p class="par">
                                                               Withdrawal or Removal of Co-IMAs, Partners and/or Owners. If a Co-IMA, Partner or Owner is removed or withdrawn from the IMA position, the remaining IMA position shall be responsible for all acts or omissions in contravention of the IMA Agreement, of those who have left the IMA position, for a period of six (6) months after the date of the departure of the Co-IMA, Partner, or Owner for purposes of this provision, the seller will be required to continue to comply with all terms post-termination obligations of the IMA Agreement.
                                                       </p>
                                                       <p class="par">
                                                               Co-Ownership over IMA positions – in case of co-ownership over certain position the COMPANY will reflect this in its files. All relations between co-owners, including future activity of the IMAs – co-owners are exclusive responsibility of the IMAs. The Company is not responsible and cannot be held liable for any unsettled legal and financial relations and/or obligations between the co-owners.
                                                       </p>
                                                </li>

                                                  <li>
                                                        <p class="par">
                                                              Name Change. An IMA may change the operating name of the IMA position by forwarding written notification to the Company. The Company reserves the right to request the Articles of Incorporation of a corporation or Partnership Agreement of a partnership, or any amendments pertaining thereto, as well as any other relevant documents or corporate or partnership documentation relating to ownership or control.
                                                       </p>
                                                </li>

                                                  <li>
                                                        <p class="par">
                                                               Changing the registering/enrolling IMA. Except as set forth in Section 2 D, changing your enrolling IMA is not allowed. The IMA commission opportunity is a business built upon sales of products for consumption and upon the creation of relationships. Once a new IMA is referred or enrolled, the Company will protect this relationship to the fullest extent possible.
                                                       </p>
                                                </li>

                                                  <li>
                                                        <p class="par">
                                                               Suspension of Commission Payments. If there is any question over the disposition of the IMA position or the income from the IMA position (whether by reason of an event described in A through J or otherwise), the Company may suspend the IMA and hold all commissions and bonuses until the dispute over the disposition is resolved by agreement between the parties or by an order from a court of competent jurisdiction.
                                                       </p>
                                                </li>
                                        </ol>

                                        <li class="secondary-header">
                                                <h2 class="secondary-header"> DELAYS</h2>
                                        </li> 

                                         <p class="par">
                                             The Company shall not be responsible for delays and failures in performance of its obligations when performance is made commercially impracticable due to circumstances beyond its reasonable control. This includes, without limitation, strikes, any difficulties, riots, wars, fires, death, curtailment of a party’s source of supply, or government decrees.
                                        </p>
                                         <p class="par">
                                             The Company shall not be liable for any delay or failure to perform its duties in the event that the business activities are impossible due to periods beyond their capacity and will not be liable for any delay due to failure to comply with the expected objectives either in the present or near future (directs or indirect) or late.
                                        </p>


                                         <li class="secondary-header">
                                                <h2 class="secondary-header"> OBJECTIONABLE ACTIVITY OR CONDUCT</h2>
                                        </li> 

                                         <p class="par">
                                             Imps may not distribute material, have written correspondence, telephone contact, voice mail and/or electronic mail that is or contains unlawful, harassing, libelous, slanderous, abusive, threatening, harmful, any violance,ideologist, vulgar, obscene, or otherwise objectionable material regarding the Company, its employees, partners or any other third party or which could otherwise give rise to civil liability or otherwise violate any applicable local, state, national or international law or regulation. In these cases the Company may cease any communication with the IMP or and it stops immediately this agreement, and undertake further legal actions if relevant.
                                        </p>

                                          <li class="secondary-header">
                                                <h2 class="secondary-header">DISPUTE RESOLUTION AND DISCIPLINARY PROCEEDINGS</h2>
                                        </li> 

                                        <ol class="alpha-li-2">
                                                <li>
                                                        <p class="par">
                                                                Disciplinary Sanctions. Violation of the present IMP Agreement, fraudulent, deceptive or unethical  conduct by any IMP may result, at Company’s discretion, in one or more of the following corrective measures:
                                                        </p>
                                                </li>

                                                <ol class="num-li-3 ml-3">
                                                        <li>
                                                                <p class="par">
                                                                        Issuance of a written warning or admonition;
                                                                </p>
                                                        </li>
                                                         <li>
                                                                <p class="par">
                                                                        Requiring the IMP to take immediate corrective measures;
                                                                </p>
                                                        </li>
                                                        <li>
                                                                <p class="par">
                                                                        Loss of rights to one or more benefit payments
                                                                </p>
                                                        </li>
                                                         <li>
                                                                <p class="par">
                                                                         Any other measure, which the Company deems practicable to implement to equitably resolve injuries caused partially or exclusively by the IMPs policy violation or contractual breach.
                                                                </p>
                                                        </li>
                                                         <li>
                                                                <p class="par">
                                                                        Any other measures expressly allowed within any provision of the General Terms and Conditions, the present IMP Agreement or allowed by law; The IMP is only entitled to a commission if he or she is not in violation of the the General Terms and Conditions and the present IMA Agreement.
                                                                </p>
                                                        </li>
                                                         
                                                </ol>

                                                 <li>
                                                        <p class="par">
                                                                Cost Effective Dispute Resolution/Waiver of Jury Trial. The Parties will act to amicably resolve questions and differences concerning structure, interpretation and effects of this IMA Agreement and other questions regarding the present agreement or the subject-matter of the latter. 
                                                        </p>
                                                         <p class="par">
                                                                Any disputes between the Company and its Imps and clients, related to and in relation to these General Terms and Conditions and regarding to its existence, governance, validity or termination, shall be referred to and finally resolved by binding arbitration under the Arbitration Rules of the Arbitration Court at the Rwanda Chamber of Commerce.
                                                        </p>

                                                        <p class="par">
                                                               Nothing in this rule shall prevent the Company from terminating the IMP Agreement or from applying to and obtaining from any court having jurisdiction a writ of attachment, a temporary injunction, preliminary injunction and/or other injunctive or emergency relief available to safeguard and protect Company’s interests prior to the filing of or during or following any arbitration or other proceeding or pending the handing down of a decision or award in connection with any arbitration or other proceeding. Nothing contained herein shall be deemed to give the arbitrator any authority, power or right to alter, change, amend, modify, add to, or to subtract from any of the provisions of the General Terms and Conditions, the present IMP Agreement
                                                        </p>

                                                        <p class="par">
                                                                The IMA shall  required to maintain good conduct as soon as it signs the agreement because as long as she or her is exposed to any misconduct, or violence, destroy’s company materials, insults, slander, quarreling with company employees, drunkenness, theft, fraud, legal proceedings, imprisonment of any kind, or any ideology, committing any form of violence that would affect the business in a timely, direct, or indirect, or in due course, and that will be the reason for the immediate termination of this agreement without prior notice in writing ,and that the company will not pay any kind compensation or any kind money to the IMP. and in the cause event of a loss on the  to effect of the business, the IMA is required to reimburse 5 times the interest that the company would have to bear on the loss incurred.
                                                        </p>
                                                </li>

                                        </ol>

                                          <li class="secondary-header">
                                                <h2 class="secondary-header"> DURATION </h2>
                                        </li> 

                                        <p class="par">
                                                This Agreement shall be effective on the date of commencement of service of the Cofounder/ investors with the COMPANY and continue in force till 10 (TEN Years after the date of termination/end of the Agreement for whatsoever reason.
                                        </p>

                                        <li class="secondary-header">
                                                <h2 class="secondary-header">   DISCLAIMER OF WARRANTIES </h2>
                                        </li> 

                                        <p class="par">
                                                Your use of the services is at your sole risk. The services are provided on an "as is" and "as available" basis. COMPANYand its affiliates expressly disclaim and exclude, to the fullest extent permitted by applicable law, all warranties, conditions and representations of any kind, whether express, implied or statutory, including, but not limited to, the implied warranties of merchantability, fitness for a particular purpose, title and non-infringement.
                                        </p>

                                        <p class="par">
                                                <span class="font-weight-bold"> COMPANY </span> and its affiliates make no warranty or condition that (i) the services will meet your requirements, (ii) the services will be uninterrupted, timely, secure, or error-free, (iii) the results that may be obtained from the use of the services will be accurate or reliable, or (iv) the quality of any products, services, information, or other material purchased or obtained by you through the services will meet your expectations.
                                        </p>


                                        <li class="secondary-header">
                                                <h2 class="secondary-header"> MISCELLANEOUS</h2>
                                        </li> 

                                        <ol class="num-li">
                                              <li>
                                                      <p class="par">
                                                                 The COFOUNDER/CUSTOMER shall not be entitled to assign its rights and/or obligations under this Agreement unless the prior written consent of the Company has been obtained. The cofounder/customer may freely subcontract Services under this Agreement, so long as the cofounder/customer remains liable for performance of the Services and its obligations under this Agreement. 
                                                      </p>
                                              </li>  

                                              <li>
                                                      <p class="par">
                                                                No Waiver of breach, failure of condition, or any right or remedy contained in or granted by the provision of this Agreement shall be effective unless it is in writing and signed by the party waiving the breach, failure, right or remedy. No waiver by the COMPANY of any breach, failure, right or remedy shall be deemed a waiver of any other breach, failure, right or remedy, whether or not similar, nor shall any waiver constitute a continuing waiver unless the writing so specifies.
                                                      </p>
                                              </li>  


                                              <li>
                                                      <p class="par">
                                                                 If any provision herein is held to be void or unenforceable, the validity and enforceability of the remaining provisions herein shall remain unaffected and enforceable.
                                                      </p>
                                              </li> 

                                               <li>
                                                      <p class="par">
                                                                This Agreement may be executed in one or more signed counterparts, facsimile or otherwise, which shall together form one instrument.
                                                      </p>
                                              </li> 
                                               <li>
                                                      <p class="par">
                                                           This Agreement may be supplemented, amended, or modified only by the mutual written Agreement of the parties. No supplement , amendment or modification of this agreement shall be binding unless it is in writing and signed by both COMPANY and the COFOUNDER/CUSTOMER.
                                                      </p>
                                              </li>  

                                               <li>
                                                      <p class="par">
                                                            If any Article of this Agreement is in conflict with any article of the other agreements and contracts between both the parties, the article of this agreement shall control.
                                                      </p>
                                              </li>  

                                               <li>
                                                      <p class="par">
                                                           The title in this Agreement is used only for convenience of reading and shall not affect the meaning of this Agreement.


                                                      </p>
                                              </li>  

                                               <li>
                                                      <p class="par">
                                                           If a Court of Competent jurisdiction holds any provision of this Agreement to be illegal, unenforceable or invalid in whole or in part for any reason, the validity and enforceability of the remaining provisions or portion of them, shall not be affected.


                                                      </p>
                                              </li>  

                                               <li>
                                                      <p class="par">
                                                            No party has been induced to enter into this Agreement by, nor is any party relying on, any representation or warranty outside those expressly set forth in this agreement.



                                                      </p>
                                              </li>  

                                               <li>
                                                      <p class="par">
                                                            Company have legal right to change, modifies, amend, addendum any clause of this Agreement without any prior approval, and COFOUNDER/CUSTOMER shall not have any issue to sign latest Agreement which shall be effective from the date of execution/sign.
                                                      </p>
                                              </li>  

                                               <li>
                                                      <p class="par">
                                                            The Agreement shall be executed in two counterparts and one counterpart shall be retained by each party and cofounder/Customer executed and signed the Agreement whether in digital/physical mode shall have validity and legal effect

                                                      </p>
                                              </li>  
                                        </ol>

                                         <p class="par">
                                           The founder/customer herewith confirms reading all the terms of this agreement and understanding it completely and having in addition received independant legal advice, and by affixing the signature below represent that this agreement is the only statement made by or on behalf of the company upon which the founder/customer has relied in signing this agreement with free will and consent without any force, duress, coercion or undue advantage.

                                      </p>
                                       <p class="par">
                                           IN WITNESS WHEREOF, the parties hereto have executed this agreement by their duly authorized representative as of the date first set forth above.

                                      </p>



                                </ol>
                                

                                <div class="sign-container">

                                        <div class="sign"> 
                                                <div>
                                                      <h3>Mperekeje kanani</h3>
            <div> <div>ID:1199080026984562</div>  <span class="empty"></span> </div>

                <p>Position (Founder and CEO)</p>
                 <p>Signature: <?php
     echo "<img src='".asset('signature/admin.png')."' style='width:60px;height:50px;'>";?> </p>

            <p>Date: <span class="date">{{date('Y/m/d')}}</span></p>  
                                                </div>
                                                
                                        </div>

                                         <div class="sign"> 
                                                <div>  
          <h3>Investor Full Name:{{$name}}  </h3>
        <div> <div>ID: {{$id}}</div>  <span class="empty"></span> </div>
        <p>Position {{$position}}</p>
        <p>Signature  <?php
// $message='admin.png';
     echo "<img src='".asset('signature/'.$message)."' style='width:60px;height:50px;'>";?></p>
            <p>Date: <span class="date">{{date('Y/m/d')}}</span></p>
                                    </div>
                                                
                                        </div>
  
                                </div>
<center>
    <form method="post" action="{{route('user.contract.confirm')}}">
    @csrf
   <div id="signature-pad">
  
     <div style="margin:10px;">
        <input type="hidden" id="signature" name="signature" value="{{$message}}">
        <button type="button" id="clear_btn" class="btn btn-danger" data-action="clear" 
        ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg><a href="{{route('user.contract')}}">&nbsp;&nbsp;Cancer contract</a>
</button> &nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" id="save_btn" class="btn btn-primary" data-action="save-png"><span class="glyphicon glyphicon-ok"></span> Confirm contract</button>
</center>
                                <div class="att">(Attorney)</div>
                                
                        </div>
                </div> 
        </div>

<script src="{{asset('assets/front/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/front/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/front/js/main.js')}}"></script>
<script src="{{asset('assets/front/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assets/front/js/jquery-migrate-3.0.1.min.js')}}"></script>
<script src="{{asset('assets/front/js/booststrap.min.js')}}"></script>
<script src="{{asset('assets/front/js/jquery.stellar.min.js')}}"></script>
<script src="{{asset('assets/front/js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/front/js/jquery.animateNumber.min.js')}}"></script>
<script src="{{asset('assets/front/js/aos.js')}}"></script>
<script src="{{asset('assets/front/js/mainj.js')}}"></script>
<script src="{{asset('assets/front/js/jquerry.min.js')}}"></script>
<script src="{{asset('assets/front/js/slick.min.js')}}"></script>
<script src="{{asset('assets/front/js/mainn.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
</script>

</body>
</html>
 