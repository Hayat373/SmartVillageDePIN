<!DOCTYPE html>
<html>
<head>
    <title>High Demand Alert</title>
    <style>
        :root {
            --primary-bg: #0a0a0f;
            --card-bg: #1d1d2d;
            --primary-accent: #00ff88;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--primary-bg);
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 25px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .logo span {
            color: var(--primary-accent);
        }
        
        .alert-icon {
            background: linear-gradient(135deg, #ff5e62, #ff9966);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .alert-icon i {
            font-size: 30px;
            color: white;
        }
        
        .content {
            margin-bottom: 25px;
        }
        
        .resource-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            background: linear-gradient(135deg, #ff5e62, #ff9966);
            font-weight: bold;
            margin: 10px 0;
        }
        
        .recommendation {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-accent);
            margin: 20px 0;
        }
        
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, var(--primary-accent), #00cc82);
            color: #000 !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">SmartVillage<span>DePIN</span></div>
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>High Demand Alert</h2>
        </div>
        
        <div class="content">
            <p>Dear User,</p>
            <p>Our AI systems have detected <strong>high demand</strong> for:</p>
            
            <div class="resource-badge">
                <i class="fas 
                    @if($resourceType == 'energy') fa-bolt 
                    @elseif($resourceType == 'bandwidth') fa-wifi 
                    @elseif($resourceType == 'water') fa-tint 
                    @elseif($resourceType == 'storage') fa-hdd 
                    @endif me-2"></i>
                {{ ucfirst($resourceType) }}
            </div>
            
            <div class="recommendation">
                <p><strong>AI Recommendation:</strong><br>{{ $recommendation }}</p>
            </div>
            
            <p>Your contribution at this time would significantly help the community meet this increased demand.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/contribute') }}" class="btn">
                    <i class="fas fa-share-alt me-2"></i> Contribute Now
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>SmartVillage DePIN Hub - Empowering Communities</p>
            <p>This is an automated alert. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>