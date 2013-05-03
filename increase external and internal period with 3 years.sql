UPDATE [carmon].[dbo].[ExternalPeriod] SET 
    Title=CAST(CAST(Title AS INT)+3 AS varchar),
    TmStampBegin=DATEADD(year,3,TmStampBegin),
    TmStampEnd=DATEADD(year,3,TmStampEnd)
    WHERE ExternalPeriodID>0;
    
UPDATE [carmon].[dbo].[InternalPeriod] SET 
    TmStampBegin=DATEADD(year,3,TmStampBegin),
    TmStampEnd=DATEADD(year,3,TmStampEnd);