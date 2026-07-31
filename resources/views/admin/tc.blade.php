@extends('admin.admin_layouts')

@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">Terms & Conditions </h6>
            
           
        </div>
        <div class="card-body" >
            <!-- <?php echo $session;?> -->
        <p style="text-align:center">This Merchant Agreement (the “Agreement”) is made on <b>{{$session->created_at}}</b> and effective from <b>{{explode(' ',$session->created_at)[0]}}</b>.</p>
        <p class="" style="text-align:center"><h5 style="text-align:center"><b>BY AND BETWEEN</b></h5></p>
        <p style="text-align:left"><b>Hyloship PRIVATE LIMITED,</b> a company incorporated under the Companies Act, 2013,
            and having its registered Office ,duly represented by its Authorized Signatory (hereinafter referred to as <b>“Hyloship”</b>
            which expression shall, unless it be repugnant to the subject or context thereof, mean and include its
            successors-in-interest, affiliates and assigns) of the <b>FIRST PARTY</b></p>
        <p class="" style="text-align:center"><h5 style="text-align:center"><b>AND</b></h5></p>   
        <p style="text-align:left">
        <b>{{$session->company_name}}</b>. a company incorporated under the Companies Act, 2013, and
            having its registered office at,<b> {{$session->company_address}}</b> duly represented
            by its Authorized Signatory (hereinafter referred to as “Company” which expression shall, unless it be
            repugnant to the subject or context thereof, mean and include its successors-in-interest, affiliates and
            permitted assigns) of the <b>SECOND PARTY</b>
        </p>
        <p style="text-align:left">Hyloship and Company are individually referred to as a “<b>Party</b>” and collectively as the “<b>Parties</b>”.</p>
        <p style="text-align:left"><b>WHEREAS</b></p>
<p>A.  The Company is engaged inter alia in the business _Ecommerce</p>
<p>B.  Hyloship is engaged in the business of logistics aggregation, e-commerce SaaS, courier related
services and other logistics verticals (“Business”)</p>
<p>C.  Hyloship is engaged in the business of logistics aggregation, e-commerce SaaS, courier related
services and other logistics verticals (“Business”).</p>
<p>D.  The Parties now enter into this written agreement to record the terms and conditions agreed to
between them in respect of the Services and certain rights and obligations between the Parties.</p>
<br/>
    <p><b>NOW THEREFORE,</b> in consideration of the foregoing and other good and valuable consideration, the
receipt and adequacy of which are hereby expressly acknowledged, the Parties, intending to be legally
bound, hereby agree as follows:</p>
<br/>
<p><b>1 <span style="text-decoration: underline;">DEFINITIONS</span></b></p>
<p>In this Agreement, the following terms, to the extent not inconsistent with the context thereof, shall have the meanings assigned to them herein below:</p>
<p>1.1. <b>&ldquo;Affiliate&rdquo;</b> shall mean, in respect to a Person, any Person, company or other entity which< controls, is controlled by or is under common control with such Person, company or other entity,</p>
<p>1.2. <b>&ldquo;Agreement&rdquo;</b> means this courier service agreement and shall include any recitals, Annexures, schedules, exhibits which may or may not be annexed to this Agreement and any amendments made by the Parties in accordance with the terms hereof.</p>
<p>1.3. <b>&ldquo;Aggrieved Party&rdquo;</b> shall have the meaning ascribed to it in Clause 12.2.</p>
<p>1.4. <b>&ldquo;Applicable Law/Laws&rdquo;</b> shall include all applicable statutes, enactments, acts of legislature, laws, ordinances, rules, bye-laws, regulations, guidelines, policies, directions, directives and orders of any Government, and applicable international treaties and regulations, in force at the relevant time.</p>
<p>1.5. <b>&ldquo;Claims&rdquo;</b> shall have the meaning ascribed to it in Clause 11.1</p>
<p>1.6. <b>&ldquo;Confidential Information&rdquo;</b> shall mean and include any and all information which is confidential to a Party including 
<p>(i) any business information, business strategies and plans;</p>
<p>(ii) any specifications, data relating to Products, processes and procedures;</p>
<p>(iii) advertising and marketing plans or marketing information, data and/or material;</p>
<p>(iv) any past, current or proposed development projects or plans for future development work;</p>
<p>(v) any technical, marketing, financial and commercial information;</p>
<p>(vi) all Company&rsquo;s Information and<p>
</p>(vii) Intellectual Property.</p>
<p>1.7. <b>&ldquo;Courier Partner&rdquo;</b> shall mean an entity or a person engaged by the Hyloship for providing on ground courier services.</p>
<p>1.8. <b>&ldquo;Defaulting Party&rdquo;</b> shall have the meaning ascribed to it in Clause 12.2.</p>
<p>1.9. <b>&ldquo;Expenses&rdquo;</b> shall have the meaning ascribed to it in Clause 6.2.</p>
<p>1.10. <b>&ldquo;Field Executive '' or &ldquo;Field Executives&rdquo;</b> means any person employed by the Courier Partner or any of its consultant / partner / sub-contractor for the purpose of delivering the Products.</p>
<p>1.11. <b>&ldquo;Identified Customer&rdquo;</b> means any person for whom the Company requests the Hyloship to arrange the delivery of Products.</p>
<p>1.12. <b>&ldquo;Intellectual Property&rdquo;</b> shall mean and include ideas, concepts, creations, discoveries, domain names, inventions, improvements, know how, trade or business secrets; patents, copyright (including all copyright in any designs and any moral rights), trademarks, service marks, designs, utility models, tools, devices, models, methods, procedures, processes, systems, principles, algorithms, works of authorship, flowcharts, drawings, books, papers, models, sketches, formulas, teaching techniques, electronic codes, proprietary techniques, research projects, and other confidential and proprietary information, computer programming code, databases, software programs, data, documents, instruction manuals, records, memoranda, notes, user guides; in either printed or machine-readable form, whether or not copyrightable or patentable, or any written orverbal instructions or comments.</p>
<p>1.13. <b>&ldquo;Intellectual Property Rights&rdquo;</b> mean and include</p><p>(i) all rights, title and interest under any statute or under common law including in any Intellectual Property or any similar rights, anywhere in the world, whether negotiable or not and whether registrable or not,</p><p> (ii) any licenses, permissions and grants in any of the foregoing; </p><p>(iii) applications for any of the foregoing and the right to apply for them in any part of the world; and </p><p>(iv) all extensions and renewals thereto.</p>
<p>1.14. <b>&ldquo;Indemnified Parties&rdquo;</b> shall have the meaning ascribed to it in Clause 11.1.</p>
<p>1.15. <b>&ldquo;Indemnifying Parties&rdquo;</b> shall have the meaning ascribed to it in Clause 11.1.</p>
<p>1.16. <b>&ldquo;Invoice&rdquo;</b> shall have the meaning ascribed to it in Clause 6.3.</p>
<p>1.17. <b>&ldquo;Material Breach&rdquo;</b> shall have the meaning ascribed to it in Clause 12.2.</p>
<p>1.18. <b>&ldquo;Person&rdquo;</b> shall mean any natural person, limited or unlimited liability company, corporation, partnership (whether limited or unlimited), proprietorship, Hindu undivided family, trust, union, association, government or any agency or political subdivision thereof or any other entity that may be treated as a person under applicable Law.</p>
<p>1.19. <b>&ldquo;Product&rdquo;</b> or <b>&ldquo;Products&rdquo;</b> shall mean the goods provided by the Company to the Field Executives for the purpose of delivery.</p>
<p>1.20. <b>&ldquo;Service&rdquo;</b> or <b>&ldquo;Services&rdquo;</b> means the delivery of Products on the request of the Company, to the Identified Customers at their desired location.</p>
<p>1.21. <b>&ldquo;Service Fee&rdquo;</b> shall have the meaning ascribed to it in Clause 6.1.</p>
<p>1.22. <b>&ldquo;Territory&rdquo;</b> shall mean any territory which is mutually agreed between the Parties to be the operational area for the performing the Services.</p>
<p>1.23. <b>&ldquo;Website&rdquo;</b> means the website of the Hyloship i.e. <b>www.hyloship.com</b> Except where the context requires otherwise, this Agreement shall be interpreted as follows:</p>
<p>(a) In addition to the above definitions, certain terms may be defined in the Recitals or elsewhere in this Agreement and wherever such terms are used in this Agreement, they shall have the meaning assigned to them.</p>
<p>(b) All references in this Agreement to statutory provisions shall be statutory provisions for the time being in force and shall be construed as including references to any statutory modifications, consolidation or re-enactment (whether before or after the Effective Date) for the time being in force and all statutory rules, regulations and orders made pursuant to a statutory provision.</p>
<p>(c) Words denoting singular shall include the plural and vice versa. Words denoting any gender shall include all genders unless the context otherwise requires.</p>
<p>(d) References to Recitals, Clauses or Annexures are, unless the context otherwise requires, references to Recitals, Clauses or Annexures to this Agreement.</p>
<p>(e) Any reference to &ldquo;writing&rdquo; shall include printing, typing, lithography and other means of reproducing words in permanent visible form.</p>
<p>(f) The terms &ldquo;include&rdquo; and &ldquo;including&rdquo; shall mean, &ldquo;include without limitation&rdquo;. The headings, sub-headings, titles, subtitles to Clauses, sub-Clauses and paragraphs are for information only, shall not form part of the operative provisions of this Agreement or the Annexure, and shall be ignored in construing the same.</p>
<p><b>2 <span style="text-decoration: underline;">SCOPE OF THE AGREEMENT</span></b></p>
<p>2.1 Subject to the terms and conditions of this Agreement, the Company hereby engages the Hyloship, on a non-exclusive basis and the Hyloship accepts such engagement, for providing the Services as per the terms of this Agreement.</p>
<p>2.2 The Hyloship shall provide the Services on &ldquo;as and when required&rdquo; basis. The Company shall raise requests for any Service in the manner agreed in this Agreement or as may be mutually agreed between the Parties.</p>
<p><b>3 <span style="text-decoration: underline;">MANNER OF PROVIDING THE SERVICES</span></b></p>
<p>3.1 The Company shall request the Hyloship for the Services as and when required, either through a call on a designated phone number of the Hyloship or directly through the Website. Provided that the Company may also request for the Services in the manner provided on the Website.</p>
<p>3.2 On the request made by the Company in the manner provided above, the Hyloship shall arrange a Field Executive to be available with the Company to provide the Services within a reasonable time depending on the availability of the Field Executives and other circumstances.</p>
<p>3.3 The Company shall hand over the duly packaged Product to the Field Executive.</p>
<p>3.4 The Field Executive will deliver the Product to the Identified Customer within a reasonable time or such time as is agreed with the Company. Provided however if there are any delays in delivery of the Product to the Identified Customer on account of bad weather, heavy or congested traffic or similar conditions then neither the Hyloship nor the Field Executive shall be responsible for any such delays.</p>
<p>3.5 Shipments which cross national borders/ international shipments may be subject to customs clearance in the destination country prior to its delivery.</p>
<p>3.6 In the event of Identified Customer cancelling its order for the Product while the Product is in transit or upon non-delivery of the Product due to the absence of the Identified Customer at the specified location, the Field Executive shall arrange to return the Product to the Company. Upon return of the Product the Company shall pay such amounts to the First Party as provided in</p>
<p><b>Schedule I.</b></p>

<p><b>4 <span style="text-decoration: underline;">ROLES AND RESPONSIBILITIES OF THE hyloship</span></b></p>
<p>4.1 <b>Quality of Service.</b> The Hyloship shall make commercially reasonable endeavours that the Services are performed in a professional and competent manner, consistent with industry standards reasonably applicable to such services.</p>
<p>4.2 <b>Performance of Services.</b> The Hyloship and not the Company, shall have the right to control the manner and means by which the Services are to be completed by the Hyloship pursuant to this Agreement. The Hyloship shall also retain the right to ensure that the Services are being performed according to the agreed specifications.</p>
<p>4.3 The Hyloship make practically reasonable endeavours that:</p>
<p>4.3.1 Field Executive shall be available with the Company within a reasonable time when required by the Company for providing the Service. However, the Hyloship shall reserve the right to reject the request made by the Company in cases of non-availability of Field Executives at that point of time.</p>
<p>4.3.2 The Products given by the Company are delivered timely and in good condition to the address of Identified Customer or any other person specified by the Company.</p>
<p>4.3.3 The money collected from the Identified Customers or any other person taking the delivery of the Product for the cash on delivery orders will be transferred to the Company&rsquo;s designated account as agreed with the seller. However, the Company shall not be held liable in case the cash on delivery amount has been delayed or misplaced by the Field Executive. The Company shall seek its claim, loss or any damages suffered from the Courier Partner directly, and in no way shall recover from the Hyloship or hold the Hyloship liable for the same. In this regard, the Company agrees that the Hyloship shall have the right to deduct the freight charges from the cash on delivery amounts received by the Hyloship, and then remit/reimburse the balance amount to the Company.</p>
<p>4.4 It is expressly understood by the Parties that the Hyloship is a mere Hyloship to the Company and not in any other capacity whatsoever it may be called. It is further agreed to by the Parties that the Hyloship is not performing any activity or job or providing service on behalf of the Company which is tantamount to seller or retailer and or stockiest/distributor. The complete activity performed by the Hyloship under this Agreement is based on specific instructions given by the Company as part of the scope defined and from time to time.</p>
<p><b>5 <span style="text-decoration: underline;">ROLES AND RESPONSIBILITIES OF THE COMPANY</span></b></p>
<p>5.1 The Company shall ensure the availability of the Products before making a request to the Hyloship for a Field Executive.</p>
<p>5.2 The Company shall duly intimate to the Hyloship of its change of ownership or change of management or change of legal status or its cessation of business.</p>
<p>5.3 The Company shall ensure that:</p>
<p>5.3.1 The products which are banned or declared illegal or the transportation of such products which require a license under the Applicable Laws, shall not be shipped through the packaged packet.</p>
<p>5.3.2 The Company agrees that it shall use good quality packaging material including tape and boxes for the packaging/sealing of the Products / shipments..Damage due to improper &amp; poor packaging will not be liable to pay the claim by hyloship.</p>
<p>5.3.3 At the time of handing over the Products to the Field Executive, the Products are in good condition. If the Products are not in good condition at the time of handling the Products, the Field Executive, at his sole discretion, may refuse to accept the Product, without any liability to the Hyloship.</p>
<p>5.3.4 The Company does not carry on activities, which are banned or illegal or immoral under the Applicable Laws.</p>
<p>5.3.5 The Company is solely liable for Products shipped through Hyloships. In the case of any Product mismatch/quality issue, Hyloship shall not be liable.</p>
<p>5.3.6 The Company shall give adequate prior intimation to the Hyloship about the nature of the Products to be transported. The Company shall also intimate the Hyloship about whether such Products are to be delicately handled, whether the Products are of hazardous nature and other conditions with which the Products have to be handled in order to facilitate the Hyloship to make adequate arrangements for transport of such Products. This prior information is critical to the Hyloship. In the event of failure to provide such information, the Hyloship shall not be held liable for any damages and in addition, the Company shall be liable to indemnify the Hyloship for any actual and direct loss/injury suffered by its authorised personnel or to its vehicles on account of such hazardous nature of Products. Additionally, the Service Provider shall have the liberty to impose a penalty of INR 50,000/- per incident for any breach</p>
<p>5.3.7 The Company shall provide proper prior intimation of the destination and details of the Identified Customer to whom/which the Products are to be delivered. Failure to provide proper information and/or any requisite information in relation to the Products, shall absolve the Hyloship of any liabilities towards loss from delay or mis-delivery. The Company agrees that it will be solely responsible for any breach of its obligations under this Agreement and for the consequences (including any loss or damage which the Company may suffer) of any such breach.</p>
<p>5.3.8 The Company shall be solely and directly liable to its Identified Customers. The Hyloship shall be liable only to the Company to the extent and in the manner set out in the Agreement. To that effect, the Company shall ensure to protect and immediately indemnify the Hyloship against any such claims from Identified Customers of the Company.</p>
<p>5.3.9 The Company shall ensure to make its premises or location of delivery of the Products, safe and accessible to the Field Executive for loading of the Products.</p>

<p>5.3.10 The Company shall be ready with the package in packed order when the Field Executive comes to receive the shipment, all pick-ups should be logged before the cut off time as directed by the customer support team of the Hyloship, and no pick up beyond the cut-off time of the Courier Partner shall be possible.</p>
<p>5.3.11 The Company shall not engage in any activity that interferes with or disrupts the Services (or the servers and networks which are connected to the Services) of the Hyloship.</p>
<p>5.4 In the event of any consumer complaint/dispute, the cases will be sent to the Company and it will be sole responsibility of the Company to resolve such cases within 24Hrs of receiving such cases. In case of any failure to resolve such cases, the Hyloship reserves the right to take appropriate action, legal or otherwise.</p>
<p><b>6 <span style="text-decoration: underline;">SERVICE FEES</span></b></p>
<p>6.1 <span style="text-decoration: underline;">Service Fee</span>. Starting from the Effective Date, as consideration in lieu of the Services provided by the Hyloship, the Company shall make a payment on a per order basis in the manner as set out in <b>Schedule I</b> of this Agreement. (&ldquo;<b>Service Fee</b>&rdquo;). The Hyloship may add new services for additional fees and charges or may proactively amend the Service Fees for existing services, at any time in its sole discretion by providing a notice, either on your dashboard or through email to the representative of the Company, which shall be considered as valid and agreed communication. Upon the Company not communicating any negative response/objection to the Hyloship to such notice, the Hyloship shall apply the modified Service Fee.</p>
<p>6.2 <span style="text-decoration: underline;">Expenses</span>. For all the expenses to be incurred by the Hyloship for the purpose of providing the Services pursuant to this Agreement, the Hyloship shall submit an expense report with the Company. The expense report shall contain a brief summary/ breakup of the expenses incurred/to be incurred and receipts thereof. The expenses shall be paid in terms of Clause 6.3 (&ldquo;<b>Expenses</b>&rdquo;).</p>
<p>6.3 <span style="text-decoration: underline;">Invoice</span>. The Hyloship is providing <b>Prepaid service</b>Amount will be deducted from the wallet or COD remittance for any Dues or for any outstanding amount of particular seller.
<!--The Hyloship shall generate and submit invoices to the Company for the Service Fee and the Expenses once a month, along with the relevant documentary proofs (&ldquo;<b>Invoice</b>&rdquo;). Invoice needs to be paid within 15 days from the date of invoice.-->
</p>
<p>6.4 <span style="text-decoration: underline;">Taxes</span>. 18% Goods and Service Tax will be applicable on the Invoice as per the Applicable Law.</p>
<p>6.5 <span style="text-decoration: underline;">Tax deduction at source (TDS)</span>. The Service Fee would be subject to statutory TDS by the Company at the applicable rate. In the event of such deduction, Company will provide the Hyloship with the such certificate of TDS, requisite information and documentation in a timely manner as per the applicable rules so as to enable claim credits of the said TDS including without limitation a statutorily prescribed certificate in this regard.</p>
<p>6.6 The Company is solely responsible for its payment of all taxes, legal compliances, and statutory registrations and reporting under Applicable Law. The Hyloship is in no way responsible for any of the Company&rsquo;s taxes or legal or statutory compliances.</p>

<p>6.7 Payment. The payment by the Company to the Hyloship shall be made within [15 (Fifteen) days] from the date of Invoice. Delayed payment of Service Fees shall attract penal interest at the rate of 2.5% per month on all outstanding Service Fees.</p>
<p>6.8 <span style="text-decoration: underline;">Right to Setoff</span>. The Company as security and compliance of its payment obligations to the Hyloship under this Agreement, hereby grants to the Hyloship the right to set-off against any moneys from time to time owing to or standing to the credit of the Company on the books of the Hyloship or in receipt of money received against cash on delivery of the Products, towards payment of any pending dues in terms of this Agreement. Regardless of the adequacy of any other remedy or rights of the Hyloship under this Agreement, the Hyloship, upon breach by the Company, or its employees/staffs or its agents, of any payment obligations to the Hyloship under this Agreement or any other agreement to which Company and the Hyloship are both parties, without notice to the Company, shall be entitled to set off all pending payment obligations of the Company to the Hyloship, then or thereafter existing, as the Hyloship may determine in its sole opinion and the Company hereby consents to such set-offs, appropriations or application.</p>
<p><b>7 <span style="text-decoration: underline;">DISPUTES REGARDING PRODUCTS/SERVICES.</span></b></p>
<p>7.1 The Hyloship shall not be responsible for any claims in connection with the late delivery of the Products. Provided the late delivery is reasonable and caused by unforeseen situations.</p>
<p>7.2 The Hyloship shall not be responsible for any damage caused to the Product or if the Product is harmed in any other manner before it comes into the possession of the Field Executives.</p>
<p>7.3 The Hyloship shall not be responsible for any damage caused to the Product in transit due to improper packaging of the Product.</p>
<p>7.4 If any dispute or claim arises on the Hyloship for the cases mentioned hereinabove, then the Company shall indemnify the Hyloship in the manner specified in Clause 11 of this Agreement.</p>
<p><b>8 <span style="text-decoration: underline;">INTELLECTUAL PROPERTY</span></b></p>
<p>8.1 The Company represents that it is the sole and exclusive owner of its Intellectual Property and has and shall have full and sufficient rights to grant to the Hyloship, the right to use and permit the use of its Intellectual Property. The Company grants the right to use its Intellectual Property to the Hyloship solely for the purpose of providing the Services under the terms of this Agreement.</p>
<p>8.2 Nothing contained in this Agreement constitutes a license in favor of the Company to use the Intellectual Property owned by the Hyloship such as, but not limited to trademarks, service marks or logos, and any use by the Company of the same shall be only with the prior written permission of the Hyloship, as the case maybe.</p>
<p><b>9 <span style="text-decoration: underline;">REPRESENTATIONS AND WARRANTIES</span></b></p>
<p>9.1 <b><span style="text-decoration: underline;">Representation by the Company:</span></b> The Company has obtained all governmental, statutory, regulatory or other consents, licences, authorisations, as per the Applicable Law, required to enter into and perform its obligations under this Agreement.</p>

<p>9.2 The Company represents to the Hyloship that no claim or litigation is pending or threatened which would prevent it from entering into this Agreement and do such acts as may be agreed under this Agreement, any element thereof or any right therein and upon knowledge of any such claim or litigation by any party, the Company shall promptly notify the Hyloship.</p>
<p>9.3 Each Party represents to the other Party hereto that:</p>
<p>9.3.1 Such Party has the full power and authority to enter into, execute and deliver this Agreement and to perform the transactions contemplated hereby and such Party is duly incorporated or organized with limited liability and existing under the laws of the jurisdiction of its incorporation or organization;</p>
<p>9.3.2 The execution and delivery by such Party of this Agreement and the performance by such Party of the transactions contemplated hereunder has been duly authorized by all necessary corporate, statutory, contractual or other action of such Party.</p>
<p>9.4 Except as set forth herein, Hyloship makes no representations, and hereby expressly disclaims all warranties, express or implied, regarding its Services or any portion thereof, including, without limitation, any implied warranty of merchantability or fitness for a particular purpose and implied warranties arising from course of dealing or course of performance.</p>
<p><b>10 <span style="text-decoration: underline;">CONFIDENTIALITY</span></b> Other than for performance of this Agreement, all communications between the Parties and/or any of them and all confidential information given to or received by any of them from any other, and all information concerning the business transactions and the financial arrangements of any Party with any entity or person with whom any of them is in a confidential relationship with regard to the matter in question which comes to the knowledge of the recipient, shall be kept confidential by the recipient unless or until the recipient can reasonably demonstrate that any such communication or confidential information is in the public domain through no fault of its own, or is required to be disclosed pursuant to the obligations of extant laws. If it is in the public domain, this obligation shall then cease in relation to the specific information concerned only.</p>
<p><b>11 <span style="text-decoration: underline;">INDEMNITY</span></b></p>
<p>11.1 The Company (&ldquo;<b>Indemnifying Party</b>&rdquo;) agrees to indemnify, defend, save and hold harmless the Hyloship, its directors, agents, officers and employees (&ldquo;<b>Indemnified Parties</b>&rdquo;) from and against all liabilities, damages, judgments, claims, costs and expenses (including, but not limited to, reasonable attorneys&rsquo; fees) (&ldquo;<b>Claims</b>&rdquo;) incurred by the Indemnified Parties as a result of or arising out of 
<p></p>(i) any breach of terms of this Agreement or of any applicable law, rules, regulations or orders of any statutory, judicial, quasi-judicial or other competent authority; or </p>
<p>(ii) any action taken by any government or other statutory, judicial, quasi-judicial or other competent authority against the Indemnified Parties for any breach or default by the Indemnifying Party; or</p>
<p>(iii) any Material Breach of the provisions of this Agreement, or</p>
<p> (iv) any action taken by any third party against the Indemnified Parties for violation of any third party rights, including third party intellectual property rights, by the Indemnifying Party.</p>

<p>11.2 Notwithstanding anything to the contrary contained under this Agreement, in any event the Hyloship shall not be liable (whether in contract, warranty, tort, including but not limited to negligence, product liability or other theory) to the Company or any other person or entity for any indirect, incidental, punitive, special, consequential or exemplary damages (including damages for loss of revenues, loss of profit, or anticipated profits, loss of goodwill, loss of business or data or cost of procurement) arising out of or in relation to this Agreement even if the Hyloship has been advised of the possibility of damages. Hyloship&rsquo;s aggregate liability to the Company under this Agreement or under any Applicable Law or equity shall be limited solely to one month&rsquo;s Service Fee payable to the Hyloship.</p>
<p>11.3 In the event the Company hands over or provides the aforesaid goods/shipments to the Field Executive, then the Hyloship or its Courier Partner shall not be responsible and liable for any loss, damage, theft or misappropriation of such Products even if Hyloship or the Courier Partner or the Field Executive has the knowledge of the same and even if such loss, damage, theft or misappropriation is caused due to any reason attributable to the Hyloship or the Courier Partner or the Field Executive.</p>
<p><b>12 <span style="text-decoration: underline;">TERM AND TERMINATION</span></b></p>
<p>12.1 This Agreement shall come into force from the Effective Date mentioned in this Agreement and shall be valid, legal and binding from the Effective Date unless terminated by either Party in accordance with this Agreement. Either Party can terminate the Agreement at any time prior to the expiry of the term for any reason whatsoever by providing an advance notice of [30 (thirty) days].</p>
<p>12.2 If at any time after the Effective Date, there is a breach of any warranties by the Company; or there is any breach or non-fulfilment by the Company (&ldquo;<b>Defaulting Party</b>&rdquo;) of its obligations under this Agreement then the Hyloship (&ldquo;<b>Aggrieved Party</b>&rdquo;) may deliver a written notice to Defaulting Party which notice shall specify a period of 15 days from the date of such notice to remedy such breach, deficiency or matter that is capable of being cured, and during such day period the Defaulting Party shall have the opportunity to remedy such breach, deficiency or matter. If the Defaulting Party does not remedy the relevant breach, deficiency or matter to the reasonable satisfaction of the Aggrieved Party by the end of the 15 (fifteen) day period, or if the relevant breach, deficiency or matter is incapable of being cured, a &ldquo;<b>Material Breach</b>&rdquo; shall be deemed to have occurred under this Agreement.</p>
<p>12.3 Upon the occurrence of a Material Breach:</p>
<p>12.3.1 all obligations of Aggrieved Party towards the Defaulting Party shall automatically lapse without any further act, deed or thing on the part of any Party; and</p>
<p>12.3.2 The Aggrieved Party may terminate this Agreement at any time, by delivering a written notice to the Defaulting Party.</p>
<p>12.4 The termination rights of the Hyloship under this Clause 12 are in addition to, and not exclusive of, any other rights and remedies that the Hyloship may have hereunder, at Applicable Law or otherwise. The rights of the Hyloship under this Agreement shall continue to be in full force and effect until such time that the Hyloship is satisfied in full that all its claims and demands under the Agreement have been met to its satisfaction.</p>

<p><b>13 <span style="text-decoration: underline;">NOTICES</span></b> All notices and other communications required or permitted hereunder shall be in writing and shall be issued by electronic mail with a copy delivered by (i) hand; or (ii) registered or certified mail, postage prepaid; or (iii) recognized courier:</p>
<p style="margin-left: 10%;">
If to the Company at:
<br> Attention : <b>{{$session->	name}}({{$session->company_name}})</b> 
<br>Address : {{$session->company_address}} 
<br>Email : {{$session->email}} <br>
<br>If to the Hyloship, at: 

</p>
<p><b>14 <span style="text-decoration: underline;">MISCELLANEOUS</span></b></p>
<p>14.1 <span style="text-decoration: underline;">Amendment.</span> No change, alteration, modification, amendment or addition to this Agreement shall be effective unless it is in writing and properly signed by both Parties.</p>
<p>14.2 <span style="text-decoration: underline;">Assignment.</span> The Company shall not assign its rights and obligations under this Agreement to a third party without the prior written consent of the Hyloship.</p>
<p>14.3 <span style="text-decoration: underline;">Entire Understanding.</span> This Agreement contains the complete and integrated understanding and agreement between the Parties hereto and supersedes any understanding, agreement or negotiation, whether oral or written, as set forth herein or in written amendments hereto duly executed by both Parties.</p>
<p>14.4 <span style="text-decoration: underline;">Force Majeure.</span> Hyloship shall not be liable for any failure or delay in its performance under this Agreement due to reasons beyond its reasonable control, including acts of war, acts of God, earthquake, flood, riot, embargo, sabotage, pandemic, governmental act or failure of the Internet. The prices may be subject to hike in the event of pandemic or due to any unavoidable circumstances. No debit claims shall be accepted for any such events.</p>
<p>14.5 <span style="text-decoration: underline;">Non-solicitation.</span> The Company agrees that during the Term and 2 (two) year thereafter, it shall, unless it secures the prior written permission of the Hyloship, directly or indirectly through its subsidiaries, affiliates or group companies, induce or attempt to induce any employee, officer, director, agent, independent contractor, customer, supplier or other Hyloship of the Hyloship to terminate its relationship with, or cease providing services or products to, or purchasing products from, the Company.</p>
<p>14.6 <span style="text-decoration: underline;">Dispute Resolution.</span> Subject to Clause 14.10, all disputes arising out of or in relation to this Agreement, including any question regarding its existence, validity or termination, which cannot be amicably resolved by the Parties within 15 (fifteen) days of being brought to their attention (such 15 (fifteen) day period is referred to as the &ldquo;Consultation Period&rdquo;), shall be referred to a sole arbitrator to be appointed by mutual consent between the Parties, and settled by arbitration governed by the provisions of Arbitration and Conciliation Act, 1996. If the Parties are not able to agree on a sole arbitrator within 10 (ten) days of the Consultation Period, a panel of three arbitrators shall be appointed wherein each Party shall appoint one arbitrator within 30 (thirty) days of the expiry of the Consultation Period, and the two arbitrators together shall appoint the presiding arbitrator within 15 (fifteen) days of the appointment of the last of the two arbitrators. The venue/seat of arbitration shall be in New Delhi, India and the language of arbitration shall be English. A dispute shall be deemed to have arisen when either Party notifies the other Party in writing to that effect.</p>
<p>14.7 <span style="text-decoration: underline;">Governing Law and Jurisdiction.</span> This Agreement shall, in all respects, be governed by and construed in all respects in accordance with the laws of India and subject to arbitration provisions contained in this Agreement, the courts in New Delhi, India shall have exclusive jurisdiction over the Dispute referred to it under this Agreement.</p>
<p>14.8 <span style="text-decoration: underline;">Attorneys&rsquo; Fees and Costs.</span> Subject to Clause 14.6, if court proceedings are required to enforce any provision or to remedy any breach of this Agreement, the prevailing Party shall be entitled to an award of reasonable and necessary expenses of litigation, including reasonable attorneys&rsquo; fees and costs.</p>
<p>14.9 <span style="text-decoration: underline;">Severability.</span> If any provision of this Agreement is void, or is so declared, such provision shall be severed from this Agreement. The Agreement shall otherwise remain in full force and effect.</p>
<p>14.10 <span style="text-decoration: underline;">Remedies.</span> The Company acknowledges, understands and agrees that should the Company breach any of its obligations contained in this Agreement, the Hyloship shall have the right to fully enforce this Agreement and the Hyloship shall be irreparably harmed and entitled to specific performance, including without limitation, an immediate issuance of a temporary restraining order or preliminary injunction (without posting a bond) enforcing this Agreement, in addition to a judgment for damages caused by any such breach, and to any other remedies provided for by applicable laws.</p>
<p>14.11 <span style="text-decoration: underline;">Counterparts.</span> This Agreement may be signed upon any number of counterparts, whether by original signature or by scan, email or facsimile, with the same effect as if the signature to any counterpart was an original signature upon the same instrument. The Company hereby agrees and undertakes that the Company is legally entitled and eligible to enter into this e-Agreement and further agrees and undertakes to be bound by and abide by this Agreement and the person accepting this Agreement, by and on behalf of the Company, is authorized signatory of the Company and is entitled and legally authorized to bind such Company on whose behalf this Agreement is being accepted. The Company hereby expressly waives all its rights to dispute the legal validity/tenability of this e-Agreement.</p>
<p>14.12 <span style="text-decoration: underline;">Waiver.</span> Waiver by one Party hereto of breach of any provision of this Agreement by the other shall not operate or be construed as a continuing waiver.</p>
<p>14.13 <span style="text-decoration: underline;">Mutual Obligations.</span> Each Party agrees to not knowingly do any act or knowingly make any statement, oral or written, which would injure the other party&rsquo;s business, its interest, or its reputation, unless required to do so in a legal proceeding by a competent court with proper jurisdiction.</p>
<p>14.14 <span style="text-decoration: underline;">Good Faith.</span> Each Party will act in good faith in the performance of its respective duties and responsibilities and will not unreasonably delay or withhold the giving of consent or approval required for the other Party under this Agreement. Each Party will provide an acceptable standard of care in its dealings with the other Party and its employees.</p>
<p>14.15 <span style="text-decoration: underline;">Survival.</span> Notwithstanding the foregoing, the provisions set forth in Clause 11 (Indemnity), Clause 10 (Confidentiality), Clause 8 (Intellectual Property), Clause 13 (Notices), Clause 14.5 (Non-Solicitation), Clause 14.6 (Dispute Resolution), Clause 14.7 (Governing Law and Jurisdiction), Clause 14.8 (Attorney&rsquo;s Fees and Costs), Clause 14.10 (Remedies) and Clause 14.15 (Survival) shall survive any termination of this Agreement.</p>
<p>14.16 <span style="text-decoration: underline;">Lost Claim.</span> In case of any loss or damage in the parcel caused due to the Hyloship, the Hyloship will provide the invoice value or INR 2000 whichever is lower. The loss or damage of the product will be considered only if there is damage in the product not the packaging. Any damage in the packaging will not entitle the other party to claim damages from the Hyloship.</p>
<p>14.17 <span style="text-decoration: underline;">Sub-contractor.</span> The Hyloship shall have the right to appoint a third-party subcontractor for rendering the Services without any prior intimation of such appointment being provided to the Company.</p>
<!--<p>14.18 <span style="text-decoration: underline;">Prohibited items.</span> If seller will dispatch prohibited items even after this agreement then he/she is legally liable to pay 50,000 per shipment as a penalty.</p>-->
<br>
<p><b>IN WITNESS WHEREOF,</b> the undersigned are duly authorized to execute this Agreement effective as of the date set forth herein above.</p>
<table style="width:100%">
    <tr style="border: 1px solid">
        <td style="border: 1px solid;width:50%;padding:1%"><b>For the Hyloship For Company</b></td>
        <td style="border: 1px solid;width:50%;padding:1%"><b>For Company</b></td>
    </tr>
    <tr style="border: 1px solid">
        <td style="border: 1px solid;width:50%;padding:1%"><b>Company Name: Aframaxlogistics</b></td>
        <td style="border: 1px solid;width:50%;padding:1%"><b>Name: {{$session->company_name}}</b></td>
    </tr>
    <tr style="border: 1px solid">
        <td style="border: 1px solid;width:50%;padding:1%"><b>By: Kapil</b></td>
        <td style="border: 1px solid;width:50%;padding:1%"><b>Name: {{$session->name}}</b></td>
    </tr>
    <tr style="border: 1px solid">
        <td style="border: 1px solid;width:50%;padding:1%"><b>Designation: Director</b></td>
        <td style="border: 1px solid;width:50%;padding:1%"><b>Designation: </b></td>
    </tr>
</table>
<br>
<p style="text-decoration: underline;"><b>SCHEDULE I</b></p>
<p style="text-align:center;text-decoration: underline;"><b>SERVICE FEE</b></p>
<p>The Company guarantees that the shipment contains none of the following banned commodities.</p>
<p>a. Perishables; Currency; Liquids &amp; Semi-liquids; Pornography; Bullion; Drugs and Narcotics (Illegal); Firearms, parts thereof and ammunition; Precious &amp; Semi-Precious Items; Radioactive Material; Commodities banned by Law at any given time.</p>

<p style="text-decoration: underline;"><b>Shipping Commercials:</b></p>

<p style="text-decoration: underline;"><b>TERMS &amp; CONDITIONS:</b></p>
<p>- I have read and understood that the above-shared commercials are exclusive of GST and the taxes and pricing is subject to change based on updates from the courier company or revisions in rates.</p>
<p>- I agree to declare the accurate weight and dimensions of my packets and also agree that no dispute can be raised due to incorrect weight and dimensions and no claim will be entertained.</p>
<p>- I understand that the volumetric weight of my package could be higher than the dead weight and agree to be billed for volumetric or dead weight, whichever is higher.</p>
<p>- I understand that I will be liable for the shipping charges and also the return charges of a shipment if returned and return charges are the same as the forward charges.</p>
<p>- In case of any reverse shipments, reverse pickup charges are 1.5 times the forward shipping charges.</p>
<p>- I agree with the fact that based on the invoice value (price) a fixed COD charge or COD percentage will be charged, whichever is higher.</p>
<p>- I understand that shipping prohibited items and dangerous goods are not permissible and I shall abide by those restrictions and be ready to pay a penalty if found shipping prohibited items. I also understand that liquid and semi-solid products cannot be sent via air and will be sent only through surface shipping.</p>
<p>- I agree and understand that I will use both shipping labels and manifest that are generated from the dashboard and not manual labels.</p>
<p>- I agree and understand that proper packaging of my products has to be done from my end. I shall be solely liable for any damage arising out of improper packaging and agree that no claim will be entertained for glassware, fragile products, concealed damages and improper packaging.</p>
<p>- I agree to pay extra charges like address correction charges and ODA charges, if applicable.</p>
<p>- I understand that billing disputes need to be escalated within 7 days from the invoice date. Disputes with respect to weight should be supported by image proof of the shipments.</p>
<p>- I understand that there is a chance that pick-up services may face issues due to operational concerns of the courier company, especially in case of new pickup locations.</p>
<p>- I understand that additional government rules and norms may be applicable while shipping to certain states and subject to change without prior intimation and will abide by them.</p>
<p>- <b>COD Remittance</b> Every Tuesday and Friday based on the remittance cycle.</p>
<p>- <b>COD Charges</b> Rs 35/- or 2% of Invoice Value ( whichever is higher )</p>
<p>- <b>Fuel Surcharge</b> 15% on above base rates</p>
<p>- <b>RTO Charges</b> Same as forward.</p>
<p>- <b>Reverse Charges</b> 1.5 times as forward.</p>
<p>- Octroi Charges (If applicable) As per government norms + Rs 5% service charge on invoice value</p>

<p>- <b>Max liability</b> for Shipping ₹ 2500 or Invoice Value whichever is minimum if applicable. Liability for Reverse QC (Quality Check) and Non-QC is limited to ₹ 1000 or 50% of the product whichever is minimum if applicable.</p>
<p>- <b>Volumetric Calculation</b> Delhivery (LxBxH)/4000 in Centimetres, DTDC (LxBxH)/4750 in Centimetres &amp; all other as (LxBxH)/5000 in Centimetres as volumetric divisor.</p>
<p>- <b>Freight weight</b> will be considered - Volumetric or Dead Weight whichever is higher.</p>
<p>- If order is not fulfilled as per committed by seller, then automatically rate will be upgraded into default rate commercial.</p>
<p>- For Glassware and Fragile products no claim would be entertained</p>
<p>- Issue needs to be raised within 24-36 hours of delivery. Damage mention is POD is mandatory for claim, and if any damage due to poor packaging will be not entertained.</p>

<p><b>Prohibited shipping items.</b></p>
<p>&bull; Documents.</p>
<p>&bull; Indian postal articles like passports, cheque books etc.</p>
<p>&bull; Currency and money transfer.</p>
<p>&bull; Liquids &amp; semi-liquids - (By Air up to 100 ML) (Allowed via surface with adequate packing).</p>
<p>&bull; Philately items.</p>
<p>&bull; Pornography.</p>
<p>&bull; Bullion.</p>
<p>&bull; Tobacco, drugs and narcotics (Illegal).</p>
<p>&bull; Firearms, parts thereof, ammunition including toy guns and related articles.</p>
<p>&bull; Precious &amp; semi-precious items (Artificial jewellery is allowed).</p>
<p>&bull; Radioactive material.</p>
<p>&bull; Commodities banned by law at any given time without prior notice.</p>
<p>&bull; All IATA restricted items and dangerous goods</p>
        </div>
        <div style="margin-left:10px">
            <span><input type="checkbox" name="billing_same_personal_address" value="1" onchange="getaddressvalue()"> I Accept terms and conditions</span>
            <br> 
            
            <button id="submitButton" class="btn btn-danger" style="color: white; height: 50px; font-size: 12px; margin: 10px; width: 135px; padding: 0; position: relative;" disabled="disabled">
    <span id="submitText" style="display: inline-block; width: 100%; height: 100%; padding: 15px 0; text-align: center;">
        Submit
    </span>
    <script>
        document.getElementById("submitButton").addEventListener("click", function() {
            window.location.href = "{{ route('admin.dashboard.accept_tc') }}";
        });
    </script>
</button>
        </div>
        <!-- <div id="timeLeft">Time left: 20 seconds</div> -->
    </button>          
    </div>    
         
</div>  
 
<script type="text/javascript">
    var countdownNum = 20;
    window.onload=function() {
      incTimer();
    }

    function incTimer(){
    //   setTimeout (function(){
    //     if(countdownNum != 1){
    //       countdownNum--;
    //       document.getElementById('timeLeft').innerHTML = 'Time left: ' + countdownNum + ' seconds';
    //       incTimer();
    //     } else {

    //       document.getElementById('submitButton').disabled = null;
    //       document.getElementById('timeLeft').innerHTML = '<a href="dashboard/accept_tc" style="color:white">I Accept!</a>';
    //     }
    //   },2000);
    }
    function getaddressvalue(){
        var checkbox = document.querySelector('input[name="billing_same_personal_address"]');
        var billingAddressInput = document.querySelector('input[name="billing_address"]');
        var isChecked = checkbox.checked;
        if (isChecked) {
            document.getElementById('submitButton').disabled = null;
        } else {
            document.getElementById('submitButton').disabled = true;
        }
    }
</script> 
@endsection


 