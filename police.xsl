<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title>Police Management - Available Officers</title>
                <style>
                    * {
                        box-sizing: border-box;
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }

                    body {
                        background-color: #ffffff;
                        color: #1e293b;
                        padding: 40px 20px;
                        display: flex;
                        justify-content: center;
                        align-items: flex-start;
                        min-height: 100vh;
                    }

                    .container {
                        width: 100%;
                        max-width: 800px;
                        text-align: center;
                    }

                    .brand-title {
                        color: #2563eb;
                        font-size: 42px;
                        font-weight: 800;
                        letter-spacing: -0.5px;
                        margin-bottom: 8px;
                    }

                    .brand-subtitle {
                        color: #1e293b;
                        font-size: 24px;
                        font-weight: 700;
                        margin-bottom: 30px;
                    }

                    .table-responsive {
                        width: 100%;
                        overflow-x: auto;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
                    }

                    .police-table {
                        width: 100%;
                        border-collapse: collapse;
                        text-align: center;
                        background-color: #ffffff;
                        border: 1px solid #bfdbfe;
                    }

                    .police-table th {
                        background-color: #dbeafe;
                        color: #1e3a8a;
                        font-size: 15px;
                        font-weight: 700;
                        padding: 14px 16px;
                        border: 1px solid #bfdbfe;
                    }

                    .police-table td {
                        padding: 14px 16px;
                        font-size: 14px;
                        color: #334155;
                        border: 1px solid #e2e8f0;
                    }

                    .police-table tr:hover {
                        background-color: #f8fafc;
                    }
                </style>
            </head>
            <body>

                <div class="container">
                    <!-- Brand Title -->
                    <h1 class="brand-title">Police Management</h1>
                    
                    <!-- Subtitle -->
                    <h2 class="brand-subtitle">Available Officers</h2>

                    <!-- XML Data Table -->
                    <div class="table-responsive">
                        <table class="police-table">
                            <thead>
                                <tr>
                                    <th>Officer ID</th>
                                    <th>Officer Name</th>
                                    <th>Designation</th>
                                    <th>Location / Station</th>
                                    <th>Salary (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <xsl:for-each select="police/officer">
                                    <tr>
                                        <td><xsl:value-of select="officerid"/></td>
                                        <td><xsl:value-of select="name"/></td>
                                        <td><xsl:value-of select="designation"/></td>
                                        <td><xsl:value-of select="station"/></td>
                                        <td><xsl:value-of select="salary"/></td>
                                    </tr>
                                </xsl:for-each>
                            </tbody>
                        </table>
                    </div>
                </div>

            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
