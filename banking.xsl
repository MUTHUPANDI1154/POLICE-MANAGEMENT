<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title>Digital Banking</title>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 80%;
                        margin: 30px auto;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 10px;
                        text-align: center;
                    }
                    th {
                        background-color: lightblue;
                    }
                    h2 {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <h2>Digital Banking - Account Details</h2>
                <table>
                    <tr>
                        <th>Account No</th>
                        <th>Name</th>
                        <th>Account Type</th>
                        <th>Balance</th>
                        <th>Branch</th>
                    </tr>
                    <xsl:for-each select="accounts/account">
                        <tr>
                            <td><xsl:value-of select="accountno"/></td>
                            <td><xsl:value-of select="name"/></td>
                            <td><xsl:value-of select="accounttype"/></td>
                            <td><xsl:value-of select="balance"/></td>
                            <td><xsl:value-of select="branch"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
