---
title: PDFA - Developing a File Format for Long-Term Preservation
---
ID: LeFurgy 2003
PDF: (afstuderen:RLG DigiNews December 15, 2003, Volume 7, Number 6.pdf|PDF)


===== Summary =====
*Association for Information and Image Management (AIIM)
*Association for Suppliers of Printing, Publishing and Converting Technologies (NPES)
*PDFA would be ideally suited for documents whose content and appearance must remain stable over long periods of time: approved standard
*One potential solution is to rely on text with markup language such as the Extensible Markup Language ([[XML]]) to preserve documents.
*But use of [[XML]] does not always ensure reproduction of the original visual appearance of documents.
#The needs of document producers.
#The needs of document users. 
#The needs of cultural heritage institutions and others concerned with long-term document preservation.

===Voordelen
*PDF addresses most of these requirements. It is widely integrated into many document producer work environments. Users are quite familiar with PDF from its ubiquitous presence on the World Wide Web. Some cultural heritage institutions favor PDF because it is based on a published specification;
*By publishing the specification, Adobe has managed to avoid a key preservation problem with most other commercial software: barriers (technical as well as legal) for users to decode information content contained in files.
*The most recent PDF version also offers a rich metadata capability known as the [[Extensible Metadata Platform]] ([[XMP]]), which is based on the [[XML]] and [[Resource Description Framework]] ([[RDF]]) specifications of the [[World Wide Web Consortium]] ([[W3C)]].

===Nadelen / Beperkingen
*unrestricted PDF is not suitable as an archival format.
*PDF documents, for example, are not required to be self-contained; certain fonts may be drawn from outside the file. 
*ISO PDF/A standard is distinct from PDF in that:
:#Audio and video content are forbidden
:#Javascript and executable file launches are prohibited
:#All fonts must be embedded and also must be legally embeddable for unlimited, universal rendering
:# Colorspaces must be specified in a device-independent manner 
:#Encryption is forbidden
*natively in PDF, converted from other digital formats, or digitized from paper or microfilm.

*The metadata section relies on XMP:  description, provenance (history of the document and its context), preservation, administration, embedded in each file as plain text, conforms to W3C, XMP permits user-defined schemas to describe metadata properties, ... 
*Currently XMP does not provide for machine-readable schemas, which severely limits validation of metadata against applicable schemas. A major problem here is the pending status of the RDF schema specification.
